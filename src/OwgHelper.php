<?php

class OwgHelper{
    private $outputFile = '/data/temperatures.csv';

    private static $consoleOutput = 'php://stdout';

    private $timezone;

    private static $grillZones = 4;
    private static $externalThermometers = 4;
    private static $dataOffset = 6;

    private $dataEnd;

    public function __construct($dataDir){
        $this->timezone = new DateTimeZone('Europe/Berlin');
        $this->dataEnd = (self::$grillZones + self::$externalThermometers) * 2 + self::$dataOffset;
        $this->outputFile = sprintf('%s/temperatures.csv',$dataDir);
    }

    public function processResult($data){
        $temperatureHash = [];

        $hexData = str_split(bin2hex($data), 2);

        $i = self::$dataOffset;
        $j = 1;
        while ($i < $this->dataEnd) {
            $lowByte    = $i;
            $highByte   = $i + 1;

            $temperatureHash[$j] = $this->parseData($hexData, $lowByte, $highByte);

            // CHECK IF FIRST RECEIVED TEMPERATURE IS 1110.1 > WRONG POP
            if($j === 1){
                if($temperatureHash[$j] === 1110.1){
                    $this->dLog('sensor 1 received impossible temperature: pop-id wrong?','ERROR');
                    exit;
                }
            }

            $j++;
            $i+=2;
        }

        return $temperatureHash;
    }

    public function parseData($data, $lowByte, $highByte){
        $temp = hexdec($data[$lowByte]) * 10 + hexdec($data[$highByte]) / 10;

        if($temp === 1500){
            return null;
        }

        return $temp;
    }

    /**
     * @throws Exception
     */
    public function saveData($data){
        $now = new DateTime('now', $this->timezone);
        $dataRow = sprintf("%s;%s\n",$now->getTimestamp(),implode(";",$data));

        if($wh = fopen($this->outputFile, "a+")){
            fwrite($wh, $dataRow);
            fclose($wh);
        }else{
            $this->dLog(sprintf('ERROR: could not open output-file %s',$this->outputFile),'ERROR');
            exit;
        }
    }

    /**
     * @throws Exception
     */
    public function dLog($message, $messageClass = null){
        $now = new DateTime('now', $this->timezone);
        $logDate = $now->format("[Y-m-d H:i:s]");

        if($messageClass === "ERROR"){
            $logDate = sprintf("\e[%s;%sm%s\e[0m",'1;37','41',$logDate);
        }

        if($messageClass === "SUCCESS"){
            $logDate = sprintf("\e[%s;%sm%s\e[0m",'1;37','42',$logDate);
        }

        file_put_contents(self::$consoleOutput, sprintf("%s %s\n",$logDate, $message));
    }
}
