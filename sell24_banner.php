<?php

if (!defined('_PS_VERSION_'))
    exit();

class sell24_banner extends Module
{
    public function __construct()
    {
        $this->name = 'sell24_banner';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'sell24.cz';
        $this->need_instance = 1;
        $this->ps_versions_compliancy = array('min' => '1.7.1.0', 'max' => _PS_VERSION_);
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('sell24 Banner Display', 'sell24_banner');
        $this->description = $this->l('This module is developed to display an banner in front panel.', 'sell24_banner');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?', 'sell24 banner');
    }


    public function install()
{
    // if (Shop::isFeatureActive())
    //     Shop::setContext(Shop::CONTEXT_ALL);

    // return parent::install() && !$this->registerHook('displayHeader') &&
    //     $this->registerHook('displayHome') && Configuration::updateValue('sell24_banner_url', 'sample text');

		// Call install parent method
		if (!parent::install())
			return 'install problem';

            // Register hooks
		if (!$this->registerHook('displayHome') ||
        !$this->registerHook('displayHeader'))
        return 'display problem';

        // Preset configuration values
        Configuration::updateValue('sell24_banner_url', 'sample text');
        return 'value problem';

}


// public function install()
// {
//     if (!parent::install()
//         || !$this->registerHook('displayHome') || !$this->registerHook('displayHeader') 
//     ) {
//         return false;
//     }
//     return true;
// } 



public function uninstall()
{
    if (!parent::uninstall() || !Configuration::deleteByName('sell24_banner_url'))
        return false;

        Configuration::deleteByName('sell24_banner_url');

    return true;
}



public function hookDisplayHeader($params)
{
    // < assign variables to template >
    $this->context->smarty->assign(
        array('sell24_bannerd' => Configuration::get('sell24_banner_url'))
    );
    return $this->display(__FILE__, 'sell24_header.tpl');
}


public function hookDisplayHome($params)
{
    // < assign variables to template >
    $this->context->smarty->assign(
        array('sell24_banner' => Configuration::get('sell24_banner_url'))
    );
    return $this->display(__FILE__, 'sell24_home.tpl');
}







public function displayForm()
{
    // < init fields for form array >
   

    // Get default language
    $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

    $fields_form = array();
    $fields_form[0]['form'] = array(
        'legend' => array(
            'title' => $this->l('Sell24 Banner Module'),
        ),
        'input' => array(
            array(
                'class' => 'rte',
                'label' => $this->l('Banner Content'),
                'autoload_rte' => true,
                'type' => 'textarea',
                'name' => 'sell24_banner_url',
                'required' => true,
                'cols' => 40,
                'rows' => 10

                ),

        ),

        'submit' => array(
            'title' => $this->l('Save'),
            'class' => 'btn btn-default pull-right'
        )
    );



    // < load helperForm >
    $helper = new HelperForm();

    // < module, token and currentIndex >
    $helper->module = $this;
    $helper->name_controller = $this->name;
    $helper->token = Tools::getAdminTokenLite('AdminModules');
    $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;


    // Language
    $helper->default_form_language = $default_lang;
    $helper->allow_employee_form_lang = $default_lang;


    // < title and toolbar >
    $helper->title = $this->displayName;
    $helper->show_toolbar = true;        // false -> remove toolbar
    $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
    $helper->submit_action = 'submit'.$this->name;
    $helper->toolbar_btn = array(
        'save' =>
            array(
                'desc' => $this->l('Save'),
                'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.
                    '&token='.Tools::getAdminTokenLite('AdminModules'),
            ),
        'back' => array(
            'href' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'),
            'desc' => $this->l('Back to list')
        )
    );

    // < load current value >
    $helper->fields_value['sell24_banner_url'] = Configuration::get('sell24_banner_url');


  

 

    return $helper->generateForm($fields_form);
}


public function getContent()
{
    $output = null;


    // < here we check if the form is submited for this module >
    if (Tools::isSubmit('submit'.$this->name)) {
        $youtube_url = strval(Tools::getValue('sell24_banner_url'));
        //$youtube_url = Tools::getValue('sell24_banner_url');

        
        // < make some validation, check if we have something in the input >
        if (!isset($youtube_url))
            $output .= $this->displayError($this->l('Please insert something in this field.'));
        else
        {
            // < this will update the value of the Configuration variable >
            Configuration::updateValue( 'sell24_banner_url', $youtube_url,true);


            // < this will display the confirmation message >
            $output .= $this->displayConfirmation($this->l('Banner updated!'));
       
        }
    }
    return $output.$this->displayForm();
}

}