<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * CodeIgniter PDF Library
 *
 * Generate PDF's in your CodeIgniter applications.
 *
 * @package			CodeIgniter
 * @subpackage		Libraries
 * @category		Libraries
 * @author			Chris Harvey
 * @license			MIT License
 * @link			https://github.com/chrisnharvey/CodeIgniter-PDF-Generator-Library
 */
require_once(dirname(__FILE__) . '/vendor/autoload.php');
class Signrequest {
	
	function __construct() {
        $this->ci =& get_instance();
    }
	
	function send_sign_request_demo(){
        $token = '4a8016915d835d7218017bdb06989e5a63ae5266';
       	
        // Configure API key authorization: Token
        $config = SignRequest\Configuration::getDefaultConfiguration()->setApiKey('Authorization', $token);
        $config = SignRequest\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Token');
        
        $apiInstance = new SignRequest\Api\SignrequestQuickCreateApi(
            // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
            // This is optional, `GuzzleHttp\Client` will be used as default.
            new GuzzleHttp\Client(),
            $config
        );
        $data = new \SignRequest\Model\SignRequestQuickCreate();
        
        $from_email = 'webexcellis@gmail.com';
        $from_email_name = 'Ankur Gupta'; 
        $signer_email_1 = 'gohel.mayur18@gmail.com';
        $signer_email_2 = 'mayur.webexcellis@gmail.com';
        $file_name = 'https://yuratest.com/uploads/Example_Contract.pdf';
        
        $temp_signer = array();
        $signer = new \SignRequest\Model\Signer();
        $signer->setEmail($signer_email_1);
        $signer->setDisplayName('Mayur Gohel');
        $signer->setFirstName('Mayur');
        $signer->setLastName('Gohel');
        // Use embedded signing 
        // embed_url_user_id has to be set in order to get back an embed_url for this signer, see: https://signrequest.com/api/v1/docs/#section/Additional-signing-methods/Embed-url
        $signer->setEmbedUrlUserId('AAA');
        // set redirect urls
        $signer->setRedirectUrl('https://yuratest.com/agency-signed-agreement/agency_sk/project_sk');  // a url to go when signed
        $signer->setRedirectUrlDeclined('https://yuratest.com/agency-decline-agreement/agency_sk/project_sk');  // a url to go when declined
        // force english language
        $signer->setLanguage('en');
        $signer->setForceLanguage(true);
        $temp_signer[] = $signer;
        
        
        $signer = new \SignRequest\Model\Signer();
        $signer->setEmail($signer_email_2);
        $signer->setDisplayName('Mayur Webexcellis');
        $signer->setFirstName('Mayur');
        $signer->setLastName('Webexcellis');
        // Use embedded signing 
        // embed_url_user_id has to be set in order to get back an embed_url for this signer, see: https://signrequest.com/api/v1/docs/#section/Additional-signing-methods/Embed-url
        $signer->setEmbedUrlUserId('BBB');
        // set redirect urls
        $signer->setRedirectUrl('https://yuratest.com/client-signed-agreement/client_sk/project_sk');  // a url to go when signed
        $signer->setRedirectUrlDeclined('https://yuratest.com/client-decline-agreement/client_sk/project_sk');  // a url to go when declined
        // force english language
        $signer->setLanguage('en');
        $signer->setForceLanguage(true);
        $temp_signer[] = $signer;
        /*echo "<pre>";
        print_r($temp_signer);
        echo "</pre>";
        exit;*/
        $data->setSigners($temp_signer);
        
        // Prefill the signer name tag that has the id 'example_signer_name'
        //$prefill_tag = array('external_id' => 'example_signer_name', 'text' => 'Some Signer Name');
        //$data->setPrefillTags(array($prefill_tag));
        
        // Change / set the document name
        $data->setName('Test Contract 2');
        // Set the document content as base64 encoded html (preferably application create pdf's themselves for ultimate control on layouts!)
        //$html = file_get_contents('./doc.html');
        //$data->setFileFromContent(base64_encode($html));
        //$data->setFileFromContentName('example.html');  // must be .html to tell SignRequest this is an html file
        //$data->setFileFromURL('https://testcrm.irepair.gr/uploads/Example_Contract.pdf');
        
        $data->setFileFromURL($file_name);
        // set the sender email
        $data->setFromEmail($from_email);
        $data->setFromEmailName($from_email_name); 
        $data->setWho('mo');
        try {
            $result = $apiInstance->signrequestQuickCreateCreate($data);
            $this->returnResultDemo($result);
        } catch (Exception $e) {
            echo 'Exception when calling SignrequestQuickCreateApi->signrequestQuickCreateCreate: ', $e->getMessage(), PHP_EOL;
        }
                 
	}
    
    function returnResultDemo($result){
        header("Content-type: application/json; charset=utf-8");
        if (is_string($result)){
            $data = array('message' => $result);
        } else {
            $data = $result."";  // this serializes the swagger codegen object to json
        }
        //echo $data;exit;
        $ret_arr = json_decode($data,true);
        echo "<pre>";
        print_r($ret_arr);exit;            
    }
    
    function check_sign_request_demo(){
        $token = '4a8016915d835d7218017bdb06989e5a63ae5266';
       	$uuid = "7b2063d8-115b-40ac-8b8e-674f1701a71b";
        
        // Configure API key authorization: Token
        $config = SignRequest\Configuration::getDefaultConfiguration()->setApiKey('Authorization', $token);
        $config = SignRequest\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Token');
        
        $apiInstance = new SignRequest\Api\SignrequestsApi(new GuzzleHttp\Client(), $config);
        $data = new \SignRequest\Model\SignRequestQuickCreate();
        
        $result = $apiInstance->signrequestsRead($uuid);
        $this->returnResultDemo($result);
	}
    
    function return_result($result){
        //header("Content-type: application/json; charset=utf-8");
        if (is_string($result)){
            $data = array('message' => $result);
        } else {
            $data = $result."";  // this serializes the swagger codegen object to json
        }
        //echo $data;exit;
        $ret_arr = json_decode($data,true);
        return $ret_arr;             
    }
    
    function send_sign_request_to_users($from_email,$from_email_name,$agreement_sign_sk,$project_sk,$agreement_contract_name,$sign_document_url,$first_signer_sk,$first_signer_email,$first_signer_first_name,$first_signer_last_name,$first_signer_display_name,$second_signer_sk,$second_signer_email,$second_signer_first_name,$second_signer_last_name,$second_signer_display_name){             
        //$token = '4a8016915d835d7218017bdb06989e5a63ae5266';
        $result = $this->ci->users_model->fn_get_all_site_constants();
		$get_all_site_constants_record_count = $result['rows'];
		$q_get_all_site_constants = $result['data'];
        $sign_request_token = $q_get_all_site_constants[0]->sign_request_token;
       	
        $ret_result_arr = array();
        if($sign_request_token != '' && !empty($sign_request_token)){
            // Configure API key authorization: Token
            $config = SignRequest\Configuration::getDefaultConfiguration()->setApiKey('Authorization', $sign_request_token);
            $config = SignRequest\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Token');
            
            $apiInstance = new SignRequest\Api\SignrequestQuickCreateApi(
                // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
                // This is optional, `GuzzleHttp\Client` will be used as default.
                new GuzzleHttp\Client(),
                $config
            );
            $data = new \SignRequest\Model\SignRequestQuickCreate();
            $temp_signer = array();
            
            $first_signer_redirect_url = $this->ci->config->item("site_url").'first-signer-signed-agreement/'.$agreement_sign_sk.'/'.$first_signer_sk;
            $first_signer_redirect_url_decliened = $this->ci->config->item("site_url").'first-signer-declined-agreement/'.$agreement_sign_sk.'/'.$first_signer_sk;            
            $signer = new \SignRequest\Model\Signer();
            $signer->setEmail($first_signer_email); 
            $signer->setDisplayName($first_signer_display_name);
            $signer->setFirstName($first_signer_first_name);
            $signer->setLastName($first_signer_last_name);           
            //$signer->setEmbedUrlUserId('AAA');
            $signer->setEmbedUrlUserId($first_signer_sk);
            $signer->setRedirectUrl($first_signer_redirect_url);  // a url to go when signed
            $signer->setRedirectUrlDeclined($first_signer_redirect_url_decliened);  // a url to go when declined
            $signer->setLanguage('en');
            $signer->setForceLanguage(true);
            $temp_signer[] = $signer;
            
            $second_signer_redirect_url = $this->ci->config->item("site_url").'second-signer-signed-agreement/'.$agreement_sign_sk.'/'.$second_signer_sk;
            $second_signer_redirect_url_decliened = $this->ci->config->item("site_url").'second-signer-declined-agreement/'.$agreement_sign_sk.'/'.$second_signer_sk;
            $signer = new \SignRequest\Model\Signer();
            $signer->setEmail($second_signer_email);
            $signer->setDisplayName($second_signer_display_name);
            $signer->setFirstName($second_signer_first_name);
            $signer->setLastName($second_signer_last_name);
            //$signer->setEmbedUrlUserId('BBB');
            $signer->setEmbedUrlUserId($second_signer_sk);
            $signer->setRedirectUrl($second_signer_redirect_url);  // a url to go when signed
            $signer->setRedirectUrlDeclined($second_signer_redirect_url_decliened);  // a url to go when declined
            $signer->setLanguage('en');
            $signer->setForceLanguage(true);
            $temp_signer[] = $signer;
            /*echo "<pre>";
            print_r($temp_signer);
            echo "</pre>";
            exit;*/
            $data->setSigners($temp_signer);
            $data->setName($agreement_contract_name);
            $data->setFileFromURL($sign_document_url);
            // set the sender email
            $data->setFromEmail($from_email);
            $data->setFromEmailName($from_email_name); 
            $data->setWho('mo');
            try {
                $result = $apiInstance->signrequestQuickCreateCreate($data);
                $ret_arr = $this->return_result($result);
                /*echo "<pre>";print_r($ret_arr);exit;*/
                $document_url = $ret_arr['signers'][1]['embed_url'];
                $document_uuid = '';
                if(!empty($document_url) && $document_url !== ''){
                    $document_url_arr = explode('/',$document_url);
                    if(!empty($document_url_arr) && $document_url_arr !== ''){
                        $document_uuid = $document_url_arr[5];    
                    }    
                }        
                $ret_result_arr['status'] = 'succ';
                $ret_result_arr['msg'] = 'succfully sent agreement document for sign.';
                $ret_result_arr['from_email'] = $ret_arr['from_email'];
                $ret_result_arr['from_email_name'] = $ret_arr['from_email_name'];
                $ret_result_arr['uuid'] = $ret_arr['uuid'];
                $ret_result_arr['document_uuid'] = $document_uuid;
                $ret_result_arr['project_sk'] = $project_sk;
                $ret_result_arr['first_signer_sk'] = $first_signer_sk;
                $ret_result_arr['first_signer_email'] = $first_signer_email;
                $ret_result_arr['first_signer_first_name'] = $first_signer_first_name;
                $ret_result_arr['first_signer_last_name'] = $first_signer_last_name;
                $ret_result_arr['first_signer_email_viewed'] = $ret_arr['signers'][1]['email_viewed'];
                $ret_result_arr['first_signer_viewed'] = $ret_arr['signers'][1]['viewed'];
                $ret_result_arr['first_signer_signed'] = $ret_arr['signers'][1]['signed'];
                $ret_result_arr['first_signer_downloaded'] = $ret_arr['signers'][1]['downloaded'];
                $ret_result_arr['first_signer_embed_url'] = $ret_arr['signers'][1]['embed_url'];         
                $ret_result_arr['second_signer_sk'] = $second_signer_sk;
                $ret_result_arr['second_signer_email'] = $second_signer_email;
                $ret_result_arr['second_signer_first_name'] = $second_signer_first_name;
                $ret_result_arr['second_signer_last_name'] = $second_signer_last_name;
                $ret_result_arr['second_signer_email_viewed'] = $ret_arr['signers'][2]['email_viewed'];
                $ret_result_arr['second_signer_viewed'] = $ret_arr['signers'][2]['viewed'];
                $ret_result_arr['second_signer_signed'] = $ret_arr['signers'][2]['signed'];
                $ret_result_arr['second_signer_downloaded'] = $ret_arr['signers'][2]['downloaded'];
                $ret_result_arr['second_signer_embed_url'] = $ret_arr['signers'][2]['embed_url'];
                 
            } catch (Exception $e) {
                $ret_result_arr['status'] = 'failed';
                $ret_result_arr['msg'] = 'Error when calling sign request api. Plase contact administrator.';
                //$ret_result_arr['msg'] = $e->getMessage();
                //echo 'Exception when calling SignrequestQuickCreateApi->signrequestQuickCreateCreate: ', $e->getMessage(), PHP_EOL;
            }            
        }else{
            $ret_result_arr['status'] = 'failed';
            $ret_result_arr['msg'] = 'Sign request token is not set by administrator. Plase contact administrator.';                
        }
        return $ret_result_arr; 
	}
    
    function check_sign_request_of_users($uuid){
        $result = $this->ci->users_model->fn_get_all_site_constants();
		$get_all_site_constants_record_count = $result['rows'];
		$q_get_all_site_constants = $result['data'];
        $sign_request_token = $q_get_all_site_constants[0]->sign_request_token;
       	
        $ret_result_arr = array();
        if($sign_request_token != '' && !empty($sign_request_token)){
            // Configure API key authorization: Token
            $config = SignRequest\Configuration::getDefaultConfiguration()->setApiKey('Authorization', $sign_request_token);
            $config = SignRequest\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Token');
            
            $apiInstance = new SignRequest\Api\SignrequestsApi(new GuzzleHttp\Client(), $config);
            $data = new \SignRequest\Model\SignRequestQuickCreate();
            
            $result = $apiInstance->signrequestsRead($uuid);
            $ret_arr = $this->return_result($result);                 
            echo "<pre>";print_r($ret_arr);exit;
            $ret_result_arr['status'] = 'succ';
            $ret_result_arr['msg'] = 'succfully checked agreement document';
            /*$ret_result_arr['agency_email_viewed'] = $ret_arr['signers'][0]['email_viewed'];
            $ret_result_arr['agency_viewed'] = $ret_arr['signers'][0]['viewed'];
            $ret_result_arr['agency_signed'] = $ret_arr['signers'][0]['signed'];
            $ret_result_arr['agency_downloaded'] = $ret_arr['signers'][0]['downloaded'];        
            $ret_result_arr['client_email_viewed'] = $ret_arr['signers'][1]['email_viewed'];
            $ret_result_arr['client_viewed'] = $ret_arr['signers'][1]['viewed'];
            $ret_result_arr['client_signed'] = $ret_arr['signers'][1]['signed'];
            $ret_result_arr['client_downloaded'] = $ret_arr['signers'][1]['downloaded'];*/
            $ret_result_arr['agency_email_viewed'] = $ret_arr['signers'][1]['email_viewed'];
            $ret_result_arr['agency_viewed'] = $ret_arr['signers'][1]['viewed'];
            $ret_result_arr['agency_signed'] = $ret_arr['signers'][1]['signed'];
            $ret_result_arr['agency_downloaded'] = $ret_arr['signers'][1]['downloaded'];        
            $ret_result_arr['client_email_viewed'] = $ret_arr['signers'][0]['email_viewed'];
            $ret_result_arr['client_viewed'] = $ret_arr['signers'][0]['viewed'];
            $ret_result_arr['client_signed'] = $ret_arr['signers'][0]['signed'];
            $ret_result_arr['client_downloaded'] = $ret_arr['signers'][0]['downloaded'];                            
        }else{
            $ret_result_arr['status'] = 'failed';
            $ret_result_arr['msg'] = 'Sign request token is not set by administrator. Plase contact administrator.';                
        }
        return $ret_result_arr;
	}
    
    function fn_check_download_document_from_sign_request($uuid){
        $result = $this->ci->users_model->fn_get_all_site_constants();
		$get_all_site_constants_record_count = $result['rows'];
		$q_get_all_site_constants = $result['data'];
        $sign_request_token = $q_get_all_site_constants[0]->sign_request_token;
       	
        $ret_result_arr = array();
        if($sign_request_token != '' && !empty($sign_request_token)){
            // Configure API key authorization: Token
            $config = SignRequest\Configuration::getDefaultConfiguration()->setApiKey('Authorization', $sign_request_token);
            $config = SignRequest\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Token');
            
            $apiInstance = new SignRequest\Api\DocumentsApi(new GuzzleHttp\Client(), $config);
            //$data = new \SignRequest\Model\SignRequestQuickCreate();
            
            $result = $apiInstance->documentsRead($uuid);
            $ret_arr = $this->return_result($result);                 
            echo "<pre>";print_r($ret_arr);exit;
            $ret_result_arr['status'] = 'succ';
            $ret_result_arr['msg'] = 'succfully checked agreement document';
            /*$ret_result_arr['agency_email_viewed'] = $ret_arr['signers'][0]['email_viewed'];
            $ret_result_arr['agency_viewed'] = $ret_arr['signers'][0]['viewed'];
            $ret_result_arr['agency_signed'] = $ret_arr['signers'][0]['signed'];
            $ret_result_arr['agency_downloaded'] = $ret_arr['signers'][0]['downloaded'];        
            $ret_result_arr['client_email_viewed'] = $ret_arr['signers'][1]['email_viewed'];
            $ret_result_arr['client_viewed'] = $ret_arr['signers'][1]['viewed'];
            $ret_result_arr['client_signed'] = $ret_arr['signers'][1]['signed'];
            $ret_result_arr['client_downloaded'] = $ret_arr['signers'][1]['downloaded'];*/
            $ret_result_arr['agency_email_viewed'] = $ret_arr['signers'][1]['email_viewed'];
            $ret_result_arr['agency_viewed'] = $ret_arr['signers'][1]['viewed'];
            $ret_result_arr['agency_signed'] = $ret_arr['signers'][1]['signed'];
            $ret_result_arr['agency_downloaded'] = $ret_arr['signers'][1]['downloaded'];        
            $ret_result_arr['client_email_viewed'] = $ret_arr['signers'][0]['email_viewed'];
            $ret_result_arr['client_viewed'] = $ret_arr['signers'][0]['viewed'];
            $ret_result_arr['client_signed'] = $ret_arr['signers'][0]['signed'];
            $ret_result_arr['client_downloaded'] = $ret_arr['signers'][0]['downloaded'];                            
        }else{
            $ret_result_arr['status'] = 'failed';
            $ret_result_arr['msg'] = 'Sign request token is not set by administrator. Plase contact administrator.';                
        }
        return $ret_result_arr;
	}
    
    function fn_download_document_from_sign_request($document_uuid){
        $result = $this->ci->users_model->fn_get_all_site_constants();
		$get_all_site_constants_record_count = $result['rows'];
		$q_get_all_site_constants = $result['data'];
        $sign_request_token = $q_get_all_site_constants[0]->sign_request_token;
       	
        $ret_result_arr = array();
        if($sign_request_token != '' && !empty($sign_request_token)){
            // Configure API key authorization: Token
            $config = SignRequest\Configuration::getDefaultConfiguration()->setApiKey('Authorization', $sign_request_token);
            $config = SignRequest\Configuration::getDefaultConfiguration()->setApiKeyPrefix('Authorization', 'Token');
            $apiInstance = new SignRequest\Api\DocumentsApi(new GuzzleHttp\Client(), $config);
            $result = $apiInstance->documentsRead($document_uuid);
            $ret_arr = $this->return_result($result);                 
            #echo "<pre>";print_r($ret_arr);exit;
            try {
                $ret_result_arr['status'] = 'succ';
                $ret_result_arr['msg'] = 'succfully called document api';
                $ret_result_arr['document_url'] = $ret_arr['url'];
                $ret_result_arr['document_original_file_url'] = $ret_arr['file_as_pdf'];
                $ret_result_arr['document_signed_file_url'] = $ret_arr['pdf'];
            } catch (Exception $e) {
                $ret_result_arr['status'] = 'failed';
                $ret_result_arr['msg'] = 'Error when calling sign request api. Plase contact administrator.';
                //$ret_result_arr['msg'] = $e->getMessage();
                //echo 'Exception when calling SignrequestQuickCreateApi->signrequestQuickCreateCreate: ', $e->getMessage(), PHP_EOL;
            }                                         
        }else{
            $ret_result_arr['status'] = 'failed';
            $ret_result_arr['msg'] = 'Sign request token is not set by administrator. Plase contact administrator.';                
        }
        return $ret_result_arr;    
    }
                               
}