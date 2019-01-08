<?php
/*
* 2019 ZIED BOUHEJBA
*

*
*  @author PrestaShop SA <bouhejbazied@gmail.com>
*  @copyright  2013 ZIED BOUHEJBA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/
if (!defined('_PS_VERSION_')){
	exit;
}


class Multigtm extends Module {

	public function __construct(){
		$this->name = 'multigtm';
		$this->tab = 'seo';
		$this->version = '1.0.0';
		$this->author = 'Zied Bouhejba';
		$this->need_instance = 0;
		$this->bootstrap = true;

		parent::__construct();
		$this->displayName = $this->l('Google tag manager for multisite');
		$this->description = $this->l('configure your GTM tag');
		$this->ps_versions_compliancy = array('min' => '1.6.0.4', 'max' => '1.7.5.0');
		$this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

	}

	/**
	 * @see Module::install()
	 */
	public function install(){
		/* Adds Module */
		return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader') &&
            $this->registerHook('displayHeader');
	}

	public function uninstall()
	{
	    if (!parent::uninstall()) {
	        return false;
	    }

	    return true;
	}

	public function getContent(){
		
		$html = '';

		$m = 3;
		if(Tools::isSubmit('submitUpdate')){
			for($i = 1 ; $i<=$m ; $i++) {
				Configuration::updateValue('GTM_Site_'.$i, Tools::getValue('gtm-site-'.$i));
				Configuration::updateValue('GTM_host_'.$i, Tools::getValue('gtm-host-'.$i));
			}
			$html .= $this->displayConfirmation($this->l('Settings updated'));
		}
		
		$html .= '<form action="'.$_SERVER['REQUEST_URI'].'" method="post" class"defaultForm form-horizontal">
			<div class="panel">
				<div class="panel-heading">
					'.$this->l('setting').'
				</div>';
		for($i = 1 ; $i<=$m; $i++) {
		$html .= '<div class="form-group row">
					<div class="col-lg-3 text-right control-label">
						'.$this->l('Site '.$i).'
					</div>
					<div class="col-lg-4">
						<input type="text" name="gtm-host-'.$i.'" value="'.Configuration::get('GTM_host_'.$i).'" placeholder="Nom du site">
					</div>
					<div class="col-lg-4">
						<input type="text" name="gtm-site-'.$i.'" value="'.Configuration::get('GTM_Site_'.$i).'" placeholder="Code Google Tag Manager">
					</div>
				</div>';
		}
				
		$html .= '<div class="form-group text-right">
				   <br><button type="submit" name="submitUpdate"  class="btn btn-default"><i class="process-icon-save"></i>'.$this->l('Enregistrer').'</button>
				</div>
			</div>
		</form>';
		return $html;
	}
	


	public function hookDisplayHeader()
    {
 		$codes = Configuration::getMultiple(['GTM_Site_1','GTM_Site_2','GTM_Site_3','GTM_host_1','GTM_host_2','GTM_host_3']);

 		for($i = 1; $i<=3; $i++){
 			if( $codes['GTM_host_'.$i] === $_SERVER['SERVER_NAME']) {
 				$this->context->smarty->assign(['code' => $codes['GTM_Site_'.$i]]);
			   	return $this->display(__FILE__, 'views/templates/front/tag.tpl');
 			}
 		}

      
    }

  
}