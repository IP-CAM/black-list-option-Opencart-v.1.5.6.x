<?php
class ControllerModuleUlogin extends Controller{
    private $error   = array(); 
    private $version = "1.1";
    private $module  = "ulogin"; 
    
    public function index(){   
        $this->load->language('module/ulogin');

        $this->document->setTitle(strip_tags($this->language->get('heading_title')));
        
        $this->load->model('module/ulogin');
        $this->load->model('setting/setting');
                
        if(($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())){
            $this->model_setting_setting->editSetting('ulogin', $this->request->post);        
                    
            $this->session->data['success'] = $this->language->get('text_success');
                        
            $this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
        }
                
        $this->data['heading_title'] = strip_tags($this->language->get('heading_title'));

        $this->data['text_enabled'] = $this->language->get('text_enabled');
        $this->data['text_disabled'] = $this->language->get('text_disabled');
        $this->data['text_preview'] = $this->language->get('text_preview');
        $this->data['text_social_tip'] = $this->language->get('text_social_tip');
        $this->data['text_copyright'] = sprintf($this->language->get('text_copyright'), $this->version);
        
        $this->data['entry_status'] = $this->language->get('entry_status');
        $this->data['entry_mobilebuttons'] = $this->language->get('entry_mobilebuttons');
        $this->data['entry_sort'] = $this->language->get('entry_sort');
        $this->data['entry_providers'] = $this->language->get('entry_providers');
        $this->data['entry_hidden'] = $this->language->get('entry_hidden');
        
        
        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['button_cancel'] = $this->language->get('button_cancel');

         if(isset($this->error['warning'])){
            $this->data['error_warning'] = $this->error['warning'];
        }else{
            $this->data['error_warning'] = '';
        }
        
        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_module'),
            'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );
        
        $this->data['breadcrumbs'][] = array(
            'text'      => strip_tags($this->language->get('heading_title')),
            'href'      => $this->url->link('module/ulogin', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );
        
        $this->data['action'] = $this->url->link('module/ulogin', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
        
        if(isset($this->request->post['ulogin_status'])){
            $this->data['ulogin_status'] = $this->request->post['ulogin_status'];
        }elseif($this->config->get('ulogin_status')){
            $this->data['ulogin_status'] = $this->config->get('ulogin_status');
        }else{
            $this->data['ulogin_status'] = 0;
        }   
        
        if(isset($this->request->post['ulogin_mobilebuttons'])){
            $this->data['ulogin_mobilebuttons'] = $this->request->post['ulogin_mobilebuttons'];
        }elseif($this->config->get('ulogin_mobilebuttons')){
            $this->data['ulogin_mobilebuttons'] = $this->config->get('ulogin_mobilebuttons');
        }else{
            $this->data['ulogin_mobilebuttons'] = 0;
        }
        
        if(isset($this->request->post['ulogin_sort'])){
            $this->data['ulogin_sort'] = $this->request->post['ulogin_sort'];
        }elseif($this->config->get('ulogin_sort')){
            $this->data['ulogin_sort'] = $this->config->get('ulogin_sort');
        }else{
            $this->data['ulogin_sort'] = "default";
        }
        
        if(isset($this->request->post['ulogin_providers'])){
            $this->data['ulogin_providers'] = $this->request->post['ulogin_providers'];
        }elseif($this->config->get('ulogin_providers')){
            $this->data['ulogin_providers'] = $this->config->get('ulogin_providers');
        }else{
            $this->data['ulogin_providers'] = "facebook,vkontakte,odnoklassniki,mailru,yandex";
        }
        
        if(isset($this->request->post['ulogin_hidden'])){
            $this->data['ulogin_hidden'] = $this->request->post['ulogin_hidden'];
        }elseif($this->config->get('ulogin_hidden')){
            $this->data['ulogin_hidden'] = $this->config->get('ulogin_hidden');
        }else{
            $this->data['ulogin_hidden'] = "google,twitter,livejournal,openid,lastfm,linkedin,liveid,soundcloud,steam,flickr,uid,youtube,webmoney,foursquare,tumblr,googleplus,dudu,vimeo,instagram,wargaming";
        }
        
        $ulogin_social_networks = array();
        $ulogin_social_networks['facebook'] = array( 'name' => 'Facebook', 'sort_order' => 0, 'providers' => 1, 'on' => 0 );
        $ulogin_social_networks['vkontakte'] = array( 'name' => 'ВКонтакте', 'sort_order' => 0, 'providers' => 1, 'on' => 0 );
        $ulogin_social_networks['odnoklassniki'] = array( 'name' => 'Одноклассники', 'sort_order' => 0, 'providers' => 1, 'on' => 0 );
        $ulogin_social_networks['mailru'] = array( 'name' => 'Mail.ru', 'sort_order' => 0, 'providers' => 1, 'on' => 0 );
        $ulogin_social_networks['yandex'] = array( 'name' => 'Яндекс', 'sort_order' => 0, 'providers' => 1, 'on' => 0 );
        $ulogin_social_networks['google'] = array( 'name' => 'Google', 'sort_order' => 0, 'providers' => 0, 'on' => 0 );
        $ulogin_social_networks['twitter'] = array( 'name' => 'Twitter', 'sort_order' => 0, 'providers' => 0, 'on' => 0 );
        $ulogin_social_networks['livejournal'] = array( 'name' => 'Живой Журнал', 'sort_order' => 0, 'providers' => 0, 'on' => 0 );
        $ulogin_social_networks['openid'] = array( 'name' => 'OpenID', 'sort_order' => 0, 'providers' => 0, 'on' => 0 );
        $ulogin_social_networks['lastfm'] = array( 'name' => 'Last Fm', 'sort_order' => 0, 'providers' => 0, 'on' => 0 );
        $ulogin_social_networks['linkedin'] = array( 'name' => 'LinkedIn', 'sort_order' => 0, 'providers' => 0, 'on' => 0 );
        $ulogin_social_networks['liveid'] = array( 'name' => 'Live ID', 'sort_order' => 0, 'providers' => 0, 'on' => 0 );
        $ulogin_social_networks['soundcloud'] = array( 'name' => 'SoundCloud', 'sort_order' => 0, 'providers' => 0, 'on' => 0 );
        $ulogin_social_networks['steam'] = array( 'name' => 'Steam', 'sort_order' => 0, 'providers' => 0, 'on' => 0 );
        $ulogin_social_networks['flickr'] = array( 'name' => 'Flickr', 'sort_order' => 0, 'providers' => 0, 'on' => 0 );
        $ulogin_social_networks['uid'] = array( 'name' => 'uID', 'sort_order' => 0, 'providers' => 0, 'on' => 0 );
        $ulogin_social_networks['youtube'] = array( 'name' => 'YouTube', 'sort_order' => 0, 'providers' => 0, 'on' => 0 );
        $ulogin_social_networks['webmoney'] = array( 'name' => 'WebMoney', 'sort_order' => 0, 'providers' => 0, 'on' => 0 );
        $ulogin_social_networks['foursquare'] = array( 'name' => 'foursquare', 'sort_order' => 0, 'providers' => 0, 'on' => 0 );
        $ulogin_social_networks['tumblr'] = array( 'name' => 'tumblr', 'sort_order' => 0, 'providers' => 0, 'on' => 0 );
        $ulogin_social_networks['googleplus'] = array( 'name' => 'Google+', 'sort_order' => 0, 'providers' => 0, 'on' => 0 );
        $ulogin_social_networks['dudu'] = array( 'name' => 'dudu', 'sort_order' => 0, 'providers' => 0, 'on' => 0 );
        $ulogin_social_networks['vimeo'] = array( 'name' => 'Vimeo', 'sort_order' => 0, 'providers' => 0, 'on' => 0 );
        $ulogin_social_networks['instagram'] = array( 'name' => 'Instagram', 'sort_order' => 0, 'providers' => 0, 'on' => 0 );
        $ulogin_social_networks['wargaming'] = array( 'name' => 'Wargaming.net', 'sort_order' => 0, 'providers' => 0, 'on' => 0 );
        
        $ulogin_social_network_i = 25;
        $providers = explode(",", $this->data['ulogin_providers']);
        foreach($providers as $provider){
            if(isset($ulogin_social_networks[$provider])){
                $ulogin_social_networks[$provider]['sort_order'] = $ulogin_social_network_i;
                $ulogin_social_networks[$provider]['providers'] = 1;
                $ulogin_social_networks[$provider]['on'] = 1;
                $ulogin_social_network_i--;
            }
        }
        
        $providers_hidden = explode(",", $this->data['ulogin_hidden']);
        foreach($providers_hidden as $provider_hidden){
            if(isset($ulogin_social_networks[$provider_hidden])){
                $ulogin_social_networks[$provider_hidden]['sort_order'] = $ulogin_social_network_i;
                $ulogin_social_networks[$provider_hidden]['providers'] = 0;
                $ulogin_social_networks[$provider_hidden]['on'] = 1;
                $ulogin_social_network_i--;
            }
        }
        
        uasort($ulogin_social_networks, array("ControllerModuleUlogin", "cmp"));
        $this->data['ulogin_social_networks'] = $ulogin_social_networks;
        
        $this->data['module_copyright'] = @file_get_contents('http://panda-code.com/module_license.php?module='.urlencode($this->module).'&ver='.urlencode($this->version).'&site='.urlencode($_SERVER['HTTP_HOST']).'&email='.urlencode($this->config->get('config_email')).'&phone='.urlencode($this->config->get('config_telephone')));
        
        $this->template = 'module/ulogin.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );
                
        $this->response->setOutput($this->render());
    }
    
    private function validate() {
        if(!$this->user->hasPermission('modify', 'module/ulogin')){
            $this->error['warning'] = $this->language->get('error_permission');
        }
        
        if(!$this->error){
            return TRUE;
        }else{
            return FALSE;
        }    
    }
    
    public function install(){
        $this->load->model('setting/setting');
        $this->load->model('module/ulogin');
        
        $data = array(
            'ulogin_status'        => 1,
            'ulogin_mobilebuttons' => 1,
            'ulogin_sort'          => "relevant",
            'ulogin_providers'     => "facebook,vkontakte,odnoklassniki,mailru,yandex",
            'ulogin_hidden'        => "google,twitter,livejournal,openid,lastfm,linkedin,liveid,soundcloud,steam,flickr,uid,youtube,webmoney,foursquare,tumblr,googleplus,dudu,vimeo,instagram,wargaming"
        );
        $this->model_setting_setting->editSetting('ulogin', $data);
        
        $colls_exit = array(
            'identity' => 0,
            'network'  => 0,
            'profile'  => 0
        );
        $colls = $this->model_module_ulogin->get_customer_colls();
        foreach($colls as $coll){
            $key = $coll['Field'];
            if(isset($colls_exit[$key])){
                $colls_exit[$key] = 1;
            }
        }
        
        $this->model_module_ulogin->add_customer_coll($colls_exit);
    }
    
    public function uninstall(){
        $this->load->model('setting/setting');
        $this->load->model('module/ulogin');
        
        $data = array(
            'ulogin_status'        => 0,
            'ulogin_mobilebuttons' => 0,
            'ulogin_sort'          => "default",
            'ulogin_providers'     => "",
            'ulogin_hidden'        => ""
        );
        $this->model_setting_setting->editSetting('ulogin', $data);
        
    }
    
    private function cmp($a, $b)
    {
        if ($a['sort_order'] == $b['sort_order']) {
            return 0;
        }
        return ($a['sort_order'] > $b['sort_order']) ? -1 : 1;
    }
}
?>
