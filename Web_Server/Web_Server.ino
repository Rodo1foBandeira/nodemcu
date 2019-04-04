#include <ESP8266WiFi.h>
#include <IRremoteESP8266.h>
#include <IRrecv.h>
#include <IRsend.h>
#include <IRutils.h>

const char* ssid = "Teste";
const char* password = "teste1234";

WiFiServer server(80);

IRrecv irrecv(12);
IRsend irsend(14);
decode_results results;

String HTTP_req;

// Pinos de leitura e transmissao de infravermelho, nao podem ser configurados aqui
const byte qtdePinosES = 6;
byte entradaA = 0;
const byte pinosES[qtdePinosES]   = { 2     , 5     , 4     , 0    , 12, 14 };
byte modoPinos[qtdePinosES] = { OUTPUT, OUTPUT, OUTPUT, INPUT_PULLUP};
const String tipoPinos[qtdePinosES] = {"DIGITAL_OUTPUT","DIGITAL_OUTPUT","DIGITAL_OUTPUT","DIGITAL_OUTPUT","INFRARED_INPUT","INFRARED_OUTPUT"};
byte statusPinos[qtdePinosES] = { 0, 0, 0, 0};
// Tipos: DIGITAL_OUTPUT, DIGITAL_INPUT, ANALOG_INPUT, ANALOG_OUTPUT, PWM_OUTPUT, INFRARED_OUTPUT, INFRARED_INPUT

void setup(){
  
   Serial.begin(115200);

    // Configura o modo dos pinos digitais
    for (int nP=0; nP < qtdePinosES; nP++) {
      if (tipoPinos[nP].indexOf("INFRARED") < 0){
        pinMode(pinosES[nP], modoPinos[nP]);
        if (tipoPinos[nP].indexOf("ANALOG") > 0 || tipoPinos[nP].indexOf("PWM") > 0){
          analogWrite(pinosES[nP], statusPinos[nP]);
        }else{
        
        }      
      }
    // Infrared deve ser configurado manualmene
    }

  // Connect to Wi-Fi network with SSID and password
  Serial.print("Connecting to ");
  Serial.println(ssid);
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  // Print local IP address and start web server
  Serial.println("");
  Serial.println("WiFi connected.");
  Serial.println("IP address: ");
  Serial.println(WiFi.localIP());
  server.begin();
  
  // Configurar infrared fora do FOR
  irrecv.enableIRIn();
  irsend.begin();
}

void loop()
{
  if (digitalRead(entradaA) == LOW){
        int i = 0;
        while(digitalRead(entradaA) == LOW && i < 25){
          delay(100);
          i++;
        }
        if (statusPinos[0] == 0){// gpio2
          digitalWrite(pinosES[0], HIGH);
          statusPinos[0] = 1;
        }else{
          digitalWrite(pinosES[0], LOW);
          statusPinos[0] = 0;
        }
      }
  WiFiClient  client = server.available();   
  if (client) { 
      boolean currentLineIsBlank = true;
      while (client.connected()) {
          if (client.available()) {
              char c = client.read();
              HTTP_req += c;
              
              if (c == '\n' && currentLineIsBlank ) { 
                String params = getURLRequest(&HTTP_req);
                
                client.println("HTTP/1.1 200 OK");
                client.println("Content-Type: application/json");
                client.println();
                
                if(params.indexOf("digital=") != -1){
                  // /?digital=13
                  // Comuta estado do pino
                  int valorParam = params.substring(10, 12).toInt();
                  for (int p = 0; p < qtdePinosES; p++){
                    if (pinosES[p] == valorParam){
                      if (statusPinos[p] == 0){
                        statusPinos[p] = 1;
                        digitalWrite(valorParam, HIGH);
                      }else{
                        digitalWrite(valorParam, LOW);
                       statusPinos[p] = 0;
                      }
                     break;
                    }
                  }
                  client.print(statusPins());
                }else if(params.indexOf("analog=") != -1){
                  // /?analog=13&ciclo=1023
                  int valorParamPWM = params.substring(9, params.indexOf("&")).toInt();
                  int valorParamCiclo = params.substring(params.lastIndexOf("=")+1, params.lastIndexOf("=")+5).toInt();            
                  for (int p = 0; p < qtdePinosES; p++){
                    if (pinosES[p] == valorParamPWM){
                      statusPinos[p] = valorParamCiclo;
                      break;
                    }              
                  }
                  analogWrite(valorParamPWM, valorParamCiclo);
                  client.print(statusPins());
                }else if(params.indexOf("getInfrared") != -1){                  
                  client.print("{\"type\":\"");
                  int tentativas = 300;
                  while (tentativas > 0){                    
                    tentativas--;
                    if (irrecv.decode(&results)){
                      if (results.rawlen < 68 ){
                        delay(100);
                        irrecv.resume();  // Receive the next value
                        continue;
                      }                      
                      if (results.decode_type == UNKNOWN) {
                        client.print("UNKNOWN");
                      } else if (results.decode_type == NEC) {
                        client.print("NEC");
                      } else if (results.decode_type == SONY) {
                        client.print("SONY");
                      } else if (results.decode_type == RC5) {
                        client.print("RC5");
                      } else if (results.decode_type == RC5X) {
                        client.print("RC5X");
                      } else if (results.decode_type == RC6) {
                        client.print("RC6");
                      } else if (results.decode_type == RCMM) {
                        client.print("RCMM");
                      } else if (results.decode_type == PANASONIC) {
                        client.print("PANASONIC\",\"address\":\"");
                        client.print(results.address, HEX);
                      } else if (results.decode_type == LG) {
                        client.print("LG");
                      } else if (results.decode_type == JVC) {
                        client.print("JVC");
                      } else if (results.decode_type == AIWA_RC_T501) {
                        client.print("AIWA_RC_T501");
                      } else if (results.decode_type == WHYNTER) {
                        client.print("WHYNTER");
                      } else if (results.decode_type == NIKAI) {
                        client.print("NIKAI");
                      }
                      client.print("\",\"code\":\"");
                      unsigned long long1 = (unsigned long)((results.value & 0xFFFF0000) >> 16 );
                      unsigned long long2 = (unsigned long)((results.value & 0x0000FFFF));
                      client.print(String(long1, HEX));
                      client.print(String(long2, HEX));
                      client.print("\",\"bits\":");
                      client.print(results.bits, DEC);
                      client.print(",\"raw\":\"");
                      for (uint16_t i = 1; i < results.rawlen; i++) {
                        if (i % 100 == 0)
                          yield();  // Preemptive yield every 100th entry to feed the WDT.
                        if (i & 1) {
                          client.print(results.rawbuf[i] * RAWTICK, DEC);
                        } else {                          
                          client.print(",");
                          client.print((uint32_t) results.rawbuf[i] * RAWTICK, DEC);
                        }
                      }
                      irrecv.resume();  // Receive the next value
                      break;
                    }else{
                      delay(100);
                    }
                  }                  
                  client.print("\"}");
                }else if(params.indexOf("setInfrared=") != -1){
                  String type = params.substring(params.indexOf("setInfrared")+12, params.indexOf("&bits"));
                  int bits = params.substring(params.indexOf("&bits")+6, params.indexOf("&code")).toInt();
                  String strCode = params.substring(params.indexOf("&code")+6, params.length());
                  uint32_t code = strtoul(strCode.c_str(), NULL, 10);
                  if (results.decode_type == UNKNOWN) {
                    //irsend.send(code, bits);
                  } else if (type == "NEC") {
                    irsend.sendNEC(code, bits);
                  } else if (type == "SONY") {
                    irsend.sendSony(code, bits);
                  } else if (type == "RC5") {
                    irsend.sendRC5(code, bits);
                  } else if (type == "RC5X") {
                    //irsend.sendRC5X(code, bits);
                  } else if (type == "RC6") {
                    irsend.sendRC6(code, bits);
                  } else if (type == "RCMM") {
                    irsend.sendRCMM(code, bits);
                  } else if (type == "PANASONIC") {
                    //client.print("PANASONIC\",\"address\":\"");
                    //client.print(results.address, HEX);
                  } else if (type == "LG") {
                    irsend.sendLG(code, bits);
                  } else if (type == "JVC") {
                    irsend.sendJVC(code, bits);
                  } else if (type == "AIWA_RC_T501") {
                    //irsend.sendAIWA_RC_T501("AIWA_RC_T501");
                  } else if (type == "WHYNTER") {
                    irsend.sendWhynter(code, bits);
                  } else if (type == "NIKAI") {
                    irsend.sendNikai(code, bits);
                  }
                }else{                  
                  client.print(statusPins());
                }
        
                  HTTP_req = "";    
                  break;
              }
              
              if (c == '\n') {
                  currentLineIsBlank = true;
              } 
              else if (c != '\r') {
                  currentLineIsBlank = false;
              }
          }
      } 
      delay(1);     
      client.stop();       
  } 
}

String getURLRequest(String *requisicao) {
int inicio, fim;
String retorno;

  inicio = requisicao->indexOf("GET") + 3;
  fim = requisicao->indexOf("HTTP/") - 1;
  retorno = requisicao->substring(inicio, fim);
  retorno.trim();

  return retorno;
}

String statusPins(){
  // Neste else retornara status e tipos de todas portas
  String retorno = "[";
  for (int nL=0; nL < qtdePinosES; nL++) {              
    // Para colocar aspas dentro de aspas usa \ antes da aspa
    retorno.concat("{\"pin\":"); 
    retorno.concat(pinosES[nL]);
    retorno.concat(", \"tipo\":\"");
    retorno.concat(tipoPinos[nL]);
    retorno.concat("\"");
    // Tipos: DIGITAL_OUTPUT, DIGITAL_INPUT, ANALOG_INPUT, ANALOG_OUTPUT, PWM_OUTPUT, INFRARED_OUTPUT, INFRARED_INPUT                    
    if (tipoPinos[nL].indexOf("INFRARED") < 0){
      retorno.concat(",\"status\":");
      retorno.concat(statusPinos[nL]);
      // {"pin":1,"tipo":"ANALOG_OUTPUT","status":1023}
      // {"pin":3,"tipo":"PWM_OUTPUT","status":1023}
    }
   // {"pin":5,"tipo":"OUTPUT_INFRARED"}
   // {"pin":7,"tipo":"INPUT_INFRARED"}

   if (nL < qtdePinosES-1){
      retorno.concat("},");
   }else{
      retorno.concat("}");
   }

  }           
  retorno.concat("]");
  return retorno;
}

