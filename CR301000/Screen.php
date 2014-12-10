<?php
class Clear {
}

class ClearResponse {
}

class GetProcessStatus {
}

class GetProcessStatusResponse {
  public $GetProcessStatusResult; // ProcessResult
}

class ProcessResult {
  public $Status; // ProcessStatus
  public $Seconds; // int
  public $Message; // string
}

class ProcessStatus {
  const NotExists = 'NotExists';
  const InProcess = 'InProcess';
  const Completed = 'Completed';
  const Aborted = 'Aborted';
}

class GetScenario {
  public $scenario; // string
}

class GetScenarioResponse {
  public $GetScenarioResult; // ArrayOfCommand
}

class Command {
  public $FieldName; // string
  public $ObjectName; // string
  public $Value; // string
  public $Commit; // boolean
  public $IgnoreError; // boolean
  public $LinkedCommand; // Command
  public $Descriptor; // ElementDescriptor
  //Workaround for PHP BUG 50675
  function __clone(){
    foreach($this as $name => $value){
        if(gettype($value)=='object'){
            $this->$name= clone($this->$name);
        }
    }
  }
}

class ElementDescriptor {
  public $DisplayName; // string
  public $IsDisabled; // boolean
  public $IsRequired; // boolean
  public $ElementType; // ElementTypes
  public $LengthLimit; // int
  public $InputMask; // string
  public $DisplayRules; // string
  public $AllowedValues; // ArrayOfString
}

class ElementTypes {
  const String = 'String';
  const AsciiString = 'AsciiString';
  const StringSelector = 'StringSelector';
  const ExplicitSelector = 'ExplicitSelector';
  const Number = 'Number';
  const Option = 'Option';
  const WideOption = 'WideOption';
  const Calendar = 'Calendar';
  const Action = 'Action';
}

class EveryValue extends Command {
}

class DeleteRow {
}

class Action extends Command {
}

class Key extends Command {
}

class RowNumber extends Command {
}

class Answer extends Command {
}

class NewRow extends Command {
}

class Field extends Command {
}

class Attachment {
}

class Value extends Command {
  public $Message; // string
  public $IsError; // boolean
}

class Parameter extends Command {
}

class GetSchema {
}

class GetSchemaResponse {
  public $GetSchemaResult; // Content
}

class Content {
  public $Actions; // Actions
  public $LeadSummary; // LeadSummary
  public $LeadDetailsSummary; // LeadDetailsSummary
  public $LeadDetailsContactInfo; // LeadDetailsContactInfo
  public $LeadDetailsComments; // LeadDetailsComments
  public $LeadDetailsAddress; // LeadDetailsAddress
  public $LeadDetails; // LeadDetails
  public $CampaignHistory; // CampaignHistory
  public $Subscriptions; // Subscriptions
  public $Relations; // Relations
  public $ActivityHistory; // ActivityHistory
}

class SetSchema {
  public $schema; // Content
}

class SetSchemaResponse {
}

class Export {
  public $commands; // ArrayOfCommand
  public $filters; // ArrayOfFilter
  public $topCount; // int
  public $includeHeaders; // boolean
  public $breakOnError; // boolean
}

class Filter {
  public $Field; // Field
  public $Condition; // FilterCondition
  public $Value; // anyType
  public $Value2; // anyType
  public $OpenBrackets; // int
  public $CloseBrackets; // int
  public $Operator; // FilterOperator
}

class FilterCondition {
  const Equals = 'Equals';
  const NotEqual = 'NotEqual';
  const Greater = 'Greater';
  const GreaterOrEqual = 'GreaterOrEqual';
  const Less = 'Less';
  const LessOrEqual = 'LessOrEqual';
  const Contain = 'Contain';
  const StartsWith = 'StartsWith';
  const EndsWith = 'EndsWith';
  const NotContain = 'NotContain';
  const Between = 'Between';
  const IsNull = 'IsNull';
  const IsNotNull = 'IsNotNull';
}

class FilterOperator {
  const _And = 'And';
  const _Or = 'Or';
}

class ExportResponse {
  public $ExportResult; // ArrayOfArrayOfString
}

class Import {
  public $commands; // ArrayOfCommand
  public $filters; // ArrayOfFilter
  public $data; // ArrayOfArrayOfString
  public $includedHeaders; // boolean
  public $breakOnError; // boolean
  public $breakOnIncorrectTarget; // boolean
}

class ImportResult {
  public $Processed; // boolean
  public $Error; // string
  public $Keys; // PrimaryKey
}

class ImportResponse {
  public $ImportResult; // ArrayOfImportResult
}

class Submit {
  public $commands; // ArrayOfCommand
}

class SubmitResponse {
  public $SubmitResult; // ArrayOfContent
}

class Login {
  public $name; // string
  public $password; // string
}

class LoginResult {
  public $Code; // ErrorCode
  public $Message; // string
  public $Session; // string
}

class ErrorCode {
  const OK = 'OK';
  const INVALID_CREDENTIALS = 'INVALID_CREDENTIALS';
  const INTERNAL_ERROR = 'INTERNAL_ERROR';
  const INVALID_API_VERSION = 'INVALID_API_VERSION';
}

class LoginResponse {
  public $LoginResult; // LoginResult
}

class SetBusinessDate {
  public $date; // dateTime
}

class SetBusinessDateResponse {
}

class SetLocaleName {
  public $localeName; // string
}

class SetLocaleNameResponse {
}

class SetSchemaMode {
  public $mode; // SchemaMode
}

class SchemaMode {
  const Basic = 'Basic';
  const Detailed = 'Detailed';
}

class SetSchemaModeResponse {
}

class PrimaryKey {
  public $LeadID; // Value
}

class Actions {
  public $Save; // Action
  public $SaveClose; // Action
  public $Cancel; // Action
  public $Insert; // Action
  public $Delete; // Action
  public $FirstLead; // Action
  public $PrevLead; // Action
  public $NextLead; // Action
  public $LastLead; // Action
  public $Action; // Action
  public $ActionMergeLead; // Action
  public $ActionNewMailActivity; // Action
  public $ActionAssign; // Action
  public $ActionConvert; // Action
  public $ActionNewTask; // Action
  public $ActionNewEvent; // Action
  public $ActionNewActivity; // Action
  public $ViewActivity; // Action
  public $RegisterActivity; // Action
  public $OpenActivityOwner; // Action
  public $ViewAllActivities; // Action
  public $ViewOnMap; // Action
  public $ViewCampaign; // Action
  public $ViewSubscription; // Action
}

class LeadSummaryServiceCommands {
  public $KeyLeadID; // Key
  public $EveryLeadID; // EveryValue
  public $DeleteRow; // DeleteRow
  public $DialogAnswer; // Answer
  public $Attachment; // Attachment
}

class LeadSummary {
  public $LeadID; // Field
  public $LeadIDDisplayName; // Field
  public $LeadClass; // Field
  public $LeadSource; // Field
  public $Status; // Field
  public $Resolution; // Field
  public $Workgroup; // Field
  public $Owner; // Field
  public $OwnerEmployeeName; // Field
  public $LastActivity; // Field
  public $Method; // Field
  public $NoteText; // Field
  public $ServiceCommands; // LeadSummaryServiceCommands
}

class LeadDetailsSummaryServiceCommands {
  public $DeleteRow; // DeleteRow
  public $DialogAnswer; // Answer
  public $Attachment; // Attachment
}

class LeadDetailsSummary {
  public $CompanyName; // Field
  public $Title; // Field
  public $FirstName; // Field
  public $LastName; // Field
  public $Position; // Field
  public $Account; // Field
  public $ParentRecord; // Field
  public $Login; // Field
  public $LoginUsername; // Field
  public $ServiceCommands; // LeadDetailsSummaryServiceCommands
}

class LeadDetailsContactInfoServiceCommands {
  public $DeleteRow; // DeleteRow
  public $DialogAnswer; // Answer
  public $Attachment; // Attachment
}

class LeadDetailsContactInfo {
  public $Phone1Type; // Field
  public $Phone1; // Field
  public $Phone2Type; // Field
  public $Phone2; // Field
  public $Phone3Type; // Field
  public $Phone3; // Field
  public $FaxType; // Field
  public $Fax; // Field
  public $Email; // Field
  public $Web; // Field
  public $DoNotFax; // Field
  public $DoNotMail; // Field
  public $NoMarketing; // Field
  public $DoNotCall; // Field
  public $DoNotEmail; // Field
  public $NoMassMail; // Field
  public $ServiceCommands; // LeadDetailsContactInfoServiceCommands
}

class LeadDetailsCommentsServiceCommands {
  public $DeleteRow; // DeleteRow
  public $DialogAnswer; // Answer
  public $Attachment; // Attachment
}

class LeadDetailsComments {
  public $Comments; // Field
  public $ServiceCommands; // LeadDetailsCommentsServiceCommands
}

class LeadDetailsAddressServiceCommands {
  public $DeleteRow; // DeleteRow
  public $DialogAnswer; // Answer
  public $Attachment; // Attachment
}

class LeadDetailsAddress {
  public $AddressLine1; // Field
  public $AddressLine2; // Field
  public $City; // Field
  public $Country; // Field
  public $State; // Field
  public $PostalCode; // Field
  public $ServiceCommands; // LeadDetailsAddressServiceCommands
}

class LeadDetailsServiceCommands {
  public $KeyAttribute; // Key
  public $NewRow; // NewRow
  public $RowNumber; // RowNumber
  public $DeleteRow; // DeleteRow
  public $DialogAnswer; // Answer
}

class LeadDetails {
  public $Attribute; // Field
  public $Value; // Field
  public $NoteText; // Field
  public $ServiceCommands; // LeadDetailsServiceCommands
}

class CampaignHistoryServiceCommands {
  public $KeyCampaignID; // Key
  public $NewRow; // NewRow
  public $RowNumber; // RowNumber
  public $DeleteRow; // DeleteRow
  public $DialogAnswer; // Answer
}

class CampaignHistory {
  public $CampaignID; // Field
  public $CampaignName; // Field
  public $Status; // Field
  public $NoteText; // Field
  public $ServiceCommands; // CampaignHistoryServiceCommands
}

class SubscriptionsServiceCommands {
  public $NewRow; // NewRow
  public $RowNumber; // RowNumber
  public $DeleteRow; // DeleteRow
  public $DialogAnswer; // Answer
}

class Subscriptions {
  public $MailList; // Field
  public $Name; // Field
  public $Description; // Field
  public $FormatType; // Field
  public $Activated; // Field
  public $NoteText; // Field
  public $ServiceCommands; // SubscriptionsServiceCommands
}

class RelationsServiceCommands {
  public $NewRow; // NewRow
  public $RowNumber; // RowNumber
  public $DeleteRow; // DeleteRow
  public $DialogAnswer; // Answer
}

class Relations {
  public $Role; // Field
  public $AccountEmployee; // Field
  public $AccountEmployeeAccountID; // Field
  public $Name; // Field
  public $Contact; // Field
  public $ContactDisplayName; // Field
  public $Email; // Field
  public $AddToCC; // Field
  public $NoteText; // Field
  public $ServiceCommands; // RelationsServiceCommands
}

class ActivityHistoryServiceCommands {
  public $NewRow; // NewRow
  public $RowNumber; // RowNumber
  public $DeleteRow; // DeleteRow
  public $DialogAnswer; // Answer
  public $Attachment; // Attachment
}

class ActivityHistory {
  public $IsCompleteIcon; // Field
  public $PriorityIcon; // Field
  public $ReminderIcon; // Field
  public $ClassIcon; // Field
  public $Type; // Field
  public $Subject; // Field
  public $Status; // Field
  public $StartDate; // Field
  public $TimeSpent; // Field
  public $CreatedByCreatedByIDCreatorDistinguishedName; // Field
  public $Workgroup; // Field
  public $AssignedTo; // Field
  public $NoteText; // Field
  public $ServiceCommands; // ActivityHistoryServiceCommands
}


/**
 * Screen class
 * 
 *  
 * 
 * @author    {author}
 * @copyright {copyright}
 * @package   {package}
 */
class Screen extends SoapClient {

  private static $classmap = array(
                                    'Clear' => 'Clear',
                                    'ClearResponse' => 'ClearResponse',
                                    'GetProcessStatus' => 'GetProcessStatus',
                                    'GetProcessStatusResponse' => 'GetProcessStatusResponse',
                                    'ProcessResult' => 'ProcessResult',
                                    'ProcessStatus' => 'ProcessStatus',
                                    'GetScenario' => 'GetScenario',
                                    'GetScenarioResponse' => 'GetScenarioResponse',
                                    'Command' => 'Command',
                                    'ElementDescriptor' => 'ElementDescriptor',
                                    'ElementTypes' => 'ElementTypes',
                                    'EveryValue' => 'EveryValue',
                                    'DeleteRow' => 'DeleteRow',
                                    'Action' => 'Action',
                                    'Key' => 'Key',
                                    'RowNumber' => 'RowNumber',
                                    'Answer' => 'Answer',
                                    'NewRow' => 'NewRow',
                                    'Field' => 'Field',
                                    'Attachment' => 'Attachment',
                                    'Value' => 'Value',
                                    'Parameter' => 'Parameter',
                                    'GetSchema' => 'GetSchema',
                                    'GetSchemaResponse' => 'GetSchemaResponse',
                                    'Content' => 'Content',
                                    'SetSchema' => 'SetSchema',
                                    'SetSchemaResponse' => 'SetSchemaResponse',
                                    'Export' => 'Export',
                                    'Filter' => 'Filter',
                                    'FilterCondition' => 'FilterCondition',
                                    'FilterOperator' => 'FilterOperator',
                                    'ExportResponse' => 'ExportResponse',
                                    'Import' => 'Import',
                                    'ImportResult' => 'ImportResult',
                                    'ImportResponse' => 'ImportResponse',
                                    'Submit' => 'Submit',
                                    'SubmitResponse' => 'SubmitResponse',
                                    'Login' => 'Login',
                                    'LoginResult' => 'LoginResult',
                                    'ErrorCode' => 'ErrorCode',
                                    'LoginResponse' => 'LoginResponse',
                                    'SetBusinessDate' => 'SetBusinessDate',
                                    'SetBusinessDateResponse' => 'SetBusinessDateResponse',
                                    'SetLocaleName' => 'SetLocaleName',
                                    'SetLocaleNameResponse' => 'SetLocaleNameResponse',
                                    'SetSchemaMode' => 'SetSchemaMode',
                                    'SchemaMode' => 'SchemaMode',
                                    'SetSchemaModeResponse' => 'SetSchemaModeResponse',
                                    'PrimaryKey' => 'PrimaryKey',
                                    'Actions' => 'Actions',
                                    'LeadSummaryServiceCommands' => 'LeadSummaryServiceCommands',
                                    'LeadSummary' => 'LeadSummary',
                                    'LeadDetailsSummaryServiceCommands' => 'LeadDetailsSummaryServiceCommands',
                                    'LeadDetailsSummary' => 'LeadDetailsSummary',
                                    'LeadDetailsContactInfoServiceCommands' => 'LeadDetailsContactInfoServiceCommands',
                                    'LeadDetailsContactInfo' => 'LeadDetailsContactInfo',
                                    'LeadDetailsCommentsServiceCommands' => 'LeadDetailsCommentsServiceCommands',
                                    'LeadDetailsComments' => 'LeadDetailsComments',
                                    'LeadDetailsAddressServiceCommands' => 'LeadDetailsAddressServiceCommands',
                                    'LeadDetailsAddress' => 'LeadDetailsAddress',
                                    'LeadDetailsServiceCommands' => 'LeadDetailsServiceCommands',
                                    'LeadDetails' => 'LeadDetails',
                                    'CampaignHistoryServiceCommands' => 'CampaignHistoryServiceCommands',
                                    'CampaignHistory' => 'CampaignHistory',
                                    'SubscriptionsServiceCommands' => 'SubscriptionsServiceCommands',
                                    'Subscriptions' => 'Subscriptions',
                                    'RelationsServiceCommands' => 'RelationsServiceCommands',
                                    'Relations' => 'Relations',
                                    'ActivityHistoryServiceCommands' => 'ActivityHistoryServiceCommands',
                                    'ActivityHistory' => 'ActivityHistory',
                                   );

  public function Screen($wsdl = "301000.xml", $options = array()) {
    foreach(self::$classmap as $key => $value) {
      if(!isset($options['classmap'][$key])) {
        $options['classmap'][$key] = $value;
      }
    }
    parent::__construct($wsdl, $options);
  }

  /**
   *  
   *
   * @param Clear $parameters
   * @return ClearResponse
   */
  public function Clear(Clear $parameters) {
    return $this->__soapCall('Clear', array($parameters),       array(
            'uri' => 'http://www.acumatica.com/typed/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param GetProcessStatus $parameters
   * @return GetProcessStatusResponse
   */
  public function GetProcessStatus(GetProcessStatus $parameters) {
    return $this->__soapCall('GetProcessStatus', array($parameters),       array(
            'uri' => 'http://www.acumatica.com/typed/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param GetScenario $parameters
   * @return GetScenarioResponse
   */
  public function GetScenario(GetScenario $parameters) {
    return $this->__soapCall('GetScenario', array($parameters),       array(
            'uri' => 'http://www.acumatica.com/typed/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param GetSchema $parameters
   * @return GetSchemaResponse
   */
  public function GetSchema(GetSchema $parameters) {
    return $this->__soapCall('GetSchema', array($parameters),       array(
            'uri' => 'http://www.acumatica.com/typed/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param SetSchema $parameters
   * @return SetSchemaResponse
   */
  public function SetSchema(SetSchema $parameters) {
    return $this->__soapCall('SetSchema', array($parameters),       array(
            'uri' => 'http://www.acumatica.com/typed/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param Export $parameters
   * @return ExportResponse
   */
  public function Export(Export $parameters) {
    return $this->__soapCall('Export', array($parameters),       array(
            'uri' => 'http://www.acumatica.com/typed/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param Import $parameters
   * @return ImportResponse
   */
  public function Import(Import $parameters) {
    return $this->__soapCall('Import', array($parameters),       array(
            'uri' => 'http://www.acumatica.com/typed/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param Submit $parameters
   * @return SubmitResponse
   */
  public function Submit(Submit $parameters) {
    return $this->__soapCall('Submit', array($parameters),       array(
            'uri' => 'http://www.acumatica.com/typed/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param Login $parameters
   * @return LoginResponse
   */
  public function Login(Login $parameters) {
    return $this->__soapCall('Login', array($parameters),       array(
            'uri' => 'http://www.acumatica.com/typed/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param SetBusinessDate $parameters
   * @return SetBusinessDateResponse
   */
  public function SetBusinessDate(SetBusinessDate $parameters) {
    return $this->__soapCall('SetBusinessDate', array($parameters),       array(
            'uri' => 'http://www.acumatica.com/typed/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param SetLocaleName $parameters
   * @return SetLocaleNameResponse
   */
  public function SetLocaleName(SetLocaleName $parameters) {
    return $this->__soapCall('SetLocaleName', array($parameters),       array(
            'uri' => 'http://www.acumatica.com/typed/',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param SetSchemaMode $parameters
   * @return SetSchemaModeResponse
   */
  public function SetSchemaMode(SetSchemaMode $parameters) {
    return $this->__soapCall('SetSchemaMode', array($parameters),       array(
            'uri' => 'http://www.acumatica.com/typed/',
            'soapaction' => ''
           )
      );
  }

}

?>
