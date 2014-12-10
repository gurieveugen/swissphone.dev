<?php

class AcumaticaGate {
    public $Client;
    public $Schema;

    public function AcumaticaGate($name, $password, $namespace = "AR303000", $host="http://px2/Test")
    {
        include($namespace.'/Screen.php');
        $this->Client = new Screen($host."/Soap/".$namespace.".asmx?WSDL" ,
                                   array('exceptions'=>true,'trace'=>1));

        $login = new Login();
        $login->name     = $name;
        $login->password = $password;

        $this->Client->Login($login);
        $this->Schema = $this->Client->GetSchema(new GetSchema());
        return($this);
    }

    public function PrepareValue($value, $command, $needcommit=false, $ignore=false)
    {
        $value_command = new Value();
        $value_command->Value = $value;
        $value_command->LinkedCommand = $command;
       // $value_command->IgnoreError = $ignore;
        if($needcommit) $value_command->Commit = true;
        return($value_command);
    }

    public function PrepareSimpleFilter($command, $condition, $value)
    {
        $filter_command = new Filter();
        $filter_command->Value =  new SoapVar($value, XSD_STRING, "string", "http://www.w3.org/2001/XMLSchema");;
        $filter_command->Field = $command;
        $filter_command->Condition = $condition;
        $filter_command->CloseBrackets = 0;
        $filter_command->OpenBrackets = 0;
        $filter_command->Operator = FilterOperator::_And;
        return($filter_command);
    }

    public function GetErrorMessage($message)
    {
        $error = $message;
        $idx = strpos($error, "--->");
        if(!($idx === false))
        {
            $error = substr($error, $idx + 7);
        }
        $idx = strpos($error, "Exception:");
        if(!($idx === false))
        {
            $error = substr($error, $idx + 10);
        }
        $idx = strpos($error, "\r\n");
        if(!($idx === false))
        {
            $error = substr($error, 0, $idx);
        }
        $idx = strpos($error, "at ScreenApi.ScreenGate");
        if(!($idx === false))
        {
            $error = substr($error, 0, $idx);
        }
        $idx = strpos($error, "---> PX.Data.PXOuterException");
        if(!($idx === false))
        {
            $error = substr($error, 0, $idx);
        }
        return $error;
    }


}
