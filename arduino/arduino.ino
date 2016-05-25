
int led = 8;
int incomingByte = 0 ; 
void setup(){
  Serial.begin(9600);
  pinMode(led, OUTPUT);
}

void loop(){
  if (Serial.available() > 0 ){
    incomingByte = Serial.read();
  }
  if (incomingByte == '1'){
    digitalWrite (led, HIGH);   
  }else if (incomingByte == '0'){
    digitalWrite (led, LOW);  
  }
    
}
