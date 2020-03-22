<?php

namespace Application\Controller;


use Application\Enumeration\requestMethod;
use Application\Lib\HTTP;
use Exception;


class leadcampcheckout extends Base
{
    private $args;

    function __construct($arguments)
    {
        //pass our arguments back to the parent for safe keeping
        $vArgs = func_get_args();
        // you can't just put func_get_args() into a function as a parameter
        call_user_func_array(array($this, 'parent::__construct'), $vArgs);

    }

    function go()
    {
/* TODO: add crf verification */
        try {
            if (HTTP::requestMethod() == requestMethod::POST) {
                $t = new \Application\Model\Table\leadcampform();
                $page = new \Application\Page\LeadCampCheckout('Checkout', true);
                $page->AddPageVariable('formData', $this->get_post_vars());

                $page->go();
            } else {
                HTTP::Redirect('/');
            }
        } catch (Exception $e) {
        }

    }


    private function get_post_vars()
    {
        $out = [
            'camper_first_name' => $_POST['camper_first_name'] ?? "",
            'camper_last_name' => $_POST['camper_last_name'] ?? "",
            'camper_age' => $_POST['camper_age'] ?? "",
            'shirtsize' => $_POST['shirtsize'] ?? "",
            'address' => $_POST['address'] ?? "",
            'address2' => $_POST['address2'] ?? "",
            'city' => $_POST['city'] ?? "",
            'state' => $_POST['state'] ?? "",
            'zip' => $_POST['zip'] ?? "",
            'primary_first_name' => $_POST['primary_first_name'] ?? "",
            'primary_last_name' => $_POST['primary_last_name'] ?? "",
            'phone_number' => $_POST['phone_number'] ?? "",
            'email' => $_POST['email'] ?? "",
            'emergency_first_name' => $_POST['emergency_first_name'] ?? "",
            'emergency_last_name' => $_POST['emergency_last_name'] ?? "",
            'emergency_phone_number' => $_POST['emergency_phone_number'] ?? "",
            'emergency_email' => $_POST['emergency_email'] ?? "",
            'camp_duration' => $_POST['camp_duration'] ?? "",
            'waiver_initials' => $_POST['waiver_initials'] ?? "",
            'insurance_company' => $_POST['insurance_company'] ?? "",
            'policy_number' => $_POST['policy_number'] ?? "",
            'allergies' => $_POST['allergies'] ?? "",
        ];
        return $out;
    }

}

