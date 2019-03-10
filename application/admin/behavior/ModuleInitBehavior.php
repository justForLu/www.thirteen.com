<?php

namespace app\admin\behavior;

/**
 * 系统行为扩展：
 */
class ModuleInitBehavior {
    protected static $actionName;
    protected static $controllerName;
    protected static $moduleName;
    protected static $method;

    /**
     * 构造方法
     * @param Request $request Request对象
     * @access public
     */
    public function __construct()
    {

    }

    // 行为扩展的执行入口必须是run
    public function run(&$params){
        self::$actionName = request()->action();
        self::$controllerName = request()->controller();
        self::$moduleName = request()->module();
        self::$method = request()->method();
        // file_put_contents ( DATA_PATH."log.txt", date ( "Y-m-d H:i:s" ) . "  " . var_export('admin_CoreProgramBehavior',true) . "\r\n", FILE_APPEND );
        $this->_initialize();
    }

    private function _initialize() {
        $this->setChanneltypeStatus();
        $this->checkInlet();
    }

    /**
     * 根据前端模板自动开启系统模型
     */
    private function setChanneltypeStatus()
    {
        /*不在以下相应的控制器和操作名里不往下执行，以便提高性能*/
        $ctlActArr = array(
            'Index@index',
            'System@clearCache',
        );
        $ctlActStr = self::$controllerName.'@'.self::$actionName;
        if (!in_array($ctlActStr, $ctlActArr) || 'GET' != self::$method) {
            return false;
        }
        /*--end*/
        
        $planPath = 'template/pc';
        $planPath = realpath($planPath);
        if (!file_exists($planPath)) {
            return false;
        }
        $ctl_name_arr = array();
        $dirRes   = opendir($planPath);
        $view_suffix = config('template.view_suffix');
        while($filename = readdir($dirRes))
        {
            if(preg_match('/^(lists|view)?_/i', $filename) == 1)
            {
                $tplname = preg_replace('/([^_]+)?_([^\.]+)\.'.$view_suffix.'$/i', '${2}', $filename);
                $ctl_name_arr[] = ucwords($tplname);
            } elseif (preg_match('/\.'.$view_suffix.'$/i', $filename) == 1) {
                $tplname = preg_replace('/\.'.$view_suffix.'$/i', '', $filename);
                $ctl_name_arr[] = ucwords($tplname);
            }
        }
        $ctl_name_arr = array_unique($ctl_name_arr);

        if (!empty($ctl_name_arr)) {
            M('Channeltype')->where('id > 0')->cache(true,null,"channeltype")->update(array('status'=>0, 'update_time'=>getTime()));
            $map = array(
                'ctl_name'  => array('IN', $ctl_name_arr),
            );
            M('Channeltype')->where($map)->cache(true,null,"channeltype")->update(array('status'=>1, 'update_time'=>getTime()));
        } 
    }

    /**
     * iis服务器自动追加URL重写，入口index.php被隐藏
     */
    private function iisInlet() {
        /*不在以下相应的控制器和操作名里不往下执行，以便提高性能*/
        $ctlActArr = array(
            'Admin@login',
            'System@clear_cache',
        );
        $ctlActStr = self::$controllerName.'@'.self::$actionName;
        $seo_inlet = tpCache('seo.seo_inlet');
        if (!in_array($ctlActStr, $ctlActArr) || 'GET' != self::$method || 1 == $seo_inlet) {
            return false;
        }
        /*--end*/

        $web_server = $_SERVER["SERVER_SOFTWARE"];
        if (stristr($web_server, 'iis')) {

            $indexRewrite = <<<EOF
<rule name="Imported Rule 1" enabled="true" stopProcessing="true">
                    <match url="^(.*)$" />
                    <conditions logicalGrouping="MatchAll">
                        <add input="{HTTP_HOST}" pattern="^(.*)$" />
                        <add input="{REQUEST_FILENAME}" matchType="IsFile" negate="true" />
                        <add input="{REQUEST_FILENAME}" matchType="IsDirectory" negate="true" />
                    </conditions>
                    <action type="Rewrite" url="index.php/{R:1}" />
                </rule>
EOF;
            if (file_exists(ROOT_PATH.'web.config')) {
                $webconfig = @file_get_contents(ROOT_PATH.'web.config');
                if (!stristr($webconfig, 'index.php/{r:')) {
                    if (stristr($webconfig, '<rules>')) {
                        $rewrite = <<<EOF

                {$indexRewrite}
            </rules>
EOF;
                        $webconfig = str_replace('</rules>', $rewrite, $webconfig);
                    } else {
                        $rewrite = <<<EOF

        <rewrite>
            <rules>
                {$indexRewrite}
            </rules>
        </rewrite>
    </system.webServer>
EOF;
                        $webconfig = str_replace('</system.webServer>', $rewrite, $webconfig);
                    }
                }
            } else {
                $webconfig = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<configuration>
    <system.webServer>
        <security>
            <requestFiltering allowDoubleEscaping="true"/>
        </security>

        <rewrite>
            <rules>
                {$indexRewrite}
            </rules>
        </rewrite>
    </system.webServer>
</configuration>
EOF;
            }

            @file_put_contents(ROOT_PATH . 'web.config', $webconfig);
        }
    }

    /**
     * 检测url入口index.php是否被重写隐藏
     */
    private function checkInlet() {
        /*不在以下相应的控制器和操作名里不往下执行，以便提高性能*/
        $ctlActArr = array(
            'Index@welcome',
            'System@clearCache',
        );
        $ctlActStr = self::$controllerName.'@'.self::$actionName;
        $cacheKey = 'admin_ModuleInitBehavior_isset_checkInlet';
        $cacheVal = cache($cacheKey);
        if (!in_array($ctlActStr, $ctlActArr) || !empty($cacheVal)) {
            return false;
        }
        cache($cacheKey, 1);
        /*--end*/

        $now_seo_inlet = 0; // 默认不隐藏入口
        
        /*检测是否支持URL重写隐藏应用的入口文件index.php*/
        try {
            $response = false;
            $url = SITE_URL.'/api/Rewrite/testing.html';
            $context = stream_context_set_default(array('http' => array('timeout' => 3,'method'=>'GET')));
            $response = @file_get_contents($url,false,$context);
/*            $ch = curl_init($url);            
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 3); // 设置cURL允许执行的最长秒数
            $response = curl_exec ($ch);
            curl_close ($ch);  */
            if ('ok' == $response) {
                $now_seo_inlet = 1;
            } else if (!empty($response) && !stristr($response, 'not found')) {
                /*解决部分空间file_get_contents获取不到自身网址数据的问题*/
                $web_server = strtolower($_SERVER['SERVER_SOFTWARE']);
                if (stristr($web_server, 'apache') && file_exists('.htaccess')) {
                    $rewriteContent = @file_get_contents(ROOT_PATH.'.htaccess');
                    if (preg_match('#\#RewriteRule(\s+)\^\(\.\*\)\$(\s+)index.php\?s=/#i', $rewriteContent)) { // 有伪静态规则，但被注释
                        $now_seo_inlet = 0;
                    } else if (preg_match('#RewriteRule(\s+)\^\(\.\*\)\$(\s+)index.php\?s=/#i', $rewriteContent)) { // 有伪静态规则，且启用
                        $now_seo_inlet = 1;
                    }
                } else if (stristr($web_server, 'microsoft-iis')) {
                    $iisArr = explode('/', $web_server);
                    $iisversion = end($iisArr);
                    if (file_exists('web.config') && floatval(7) < floatval($iisversion)) {
                        $rewriteContent = @file_get_contents(ROOT_PATH.'web.config');
                        if (preg_match('#url(\s*)=(\s*)("|\')index.php/{r:#i', $rewriteContent)) {
                            $now_seo_inlet = 1;
                        }
                    }
                }
                /*--end*/
            }
        } catch (Exception $e) {}
        /*--end*/

        $seo_inlet = tpCache('seo.seo_inlet');
        if ($seo_inlet != $now_seo_inlet) {
            tpCache('seo', array('seo_inlet'=>$now_seo_inlet));
        }
    }

    /**
     * 修改数据库配置文件
     */
    private function update_databasefile()
    {
        /*不在以下相应的控制器和操作名里不往下执行，以便提高性能*/
        $ctlActArr = array(
            'Index@welcome',
            'Tools@index',
        );
        $ctlActStr = self::$controllerName.'@'.self::$actionName;
        if (!in_array($ctlActStr, $ctlActArr) || 'GET' != self::$method) {
            return false;
        }
        /*--end*/

        //读取配置文件，并替换真实配置数据1
        $databaseConf = include APP_PATH . 'database.php';
        $sampleConf = include APP_PATH . 'database.php_read';
        if ($databaseConf['break_reconnect'] != $sampleConf['break_reconnect']) {
            $strConfig = @file_get_contents(APP_PATH . 'database.php_read');
            if (false != $strConfig) {
                $strConfig = str_replace('#DB_HOST#', $databaseConf['hostname'], $strConfig);
                $strConfig = str_replace('#DB_NAME#', $databaseConf['database'], $strConfig);
                $strConfig = str_replace('#DB_USER#', $databaseConf['username'], $strConfig);
                $strConfig = str_replace('#DB_PWD#', $databaseConf['password'], $strConfig);
                $strConfig = str_replace('#DB_PORT#', $databaseConf['hostport'], $strConfig);
                $strConfig = str_replace('#DB_PREFIX#', $databaseConf['prefix'], $strConfig);
                $strConfig = str_replace('#DB_CHARSET#', $databaseConf['charset'], $strConfig);
                @chmod(APP_PATH . 'database.php',0777); //数据库配置文件的地址
                @file_put_contents(APP_PATH . 'database.php', $strConfig); //数据库配置文件的地址
            }
        }
    }

    /**
     * 根据IP判断是否本地局域网访问
     */
    private function is_local($ip){
        if(preg_match('/^(localhost|127\.|192\.)/', $ip) === 1){  
            return true;      
        }else{  
            return false;     
        }     
    }
}