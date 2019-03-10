<?php
/**
 * 拾叁网络科技
 * 
 * Date: 2019-02-13
 */

namespace app\admin\logic;

use think\Model;
use think\Db;
 
class UpgradeLogic extends Model
{
    public $app_path;
    public $data_path;
    public $version_txt_path;
    public $curent_version;    
    public $service_url;
    public $upgrade_url;
    public $service_ey;
    
    /**
     * 析构函数
     */
    function  __construct() {
         
        $this->service_ey = config('service_ey');
        $this->app_path = ROOT_PATH; // 
        $this->data_path = DATA_PATH; // 
        $this->version_txt_path = $this->data_path.'conf'.DS.'version.txt'; // 版本文件路径
        $this->curent_version = getCmsVersion();
        // api_Service_checkVersion
        $tmp_str = 'L2luZGV4LnBocD9tPWFwaSZjPVNlcnZpY2UmYT1jaGVja1ZlcnNpb24=';
        $this->service_url = base64_decode($this->service_ey).base64_decode($tmp_str);
        $this->upgrade_url = $this->service_url . '&domain='.DOMAIN.'&v=' . $this->curent_version;
    }

    /**
     * 检查是否有更新包
     * @return type 提示语
     */
    public  function checkVersion() { 
        
        $ctl_act_list = array(
            'index@welcome',
            'upgrade@welcome',
        );
        $ctl_act_str = strtolower(CONTROLLER_NAME).'@'.strtolower(ACTION_NAME);
        if(!in_array($ctl_act_str, $ctl_act_list))  
        {
            return false;
        }
        //error_reporting(0);//关闭所有错误报告        
        $url = $this->upgrade_url; 
        $context = stream_context_set_default(array('http' => array('timeout' => 3,'method'=>'GET')));
        $serviceVersionList = @file_get_contents($url,false,$context);    
        $serviceVersionList = json_decode($serviceVersionList,true);
        if(!empty($serviceVersionList))
        {
            $upgradeArr = array();
            $introStr = '';
            $upgradeStr = '';
            foreach ($serviceVersionList as $key => $val) {
                $upgrade = !empty($val['upgrade']) ? $val['upgrade'] : array();
                $upgradeArr = array_merge($upgradeArr, $upgrade);
                $introStr .= '<br/>'.filter_line_return($val['intro'], '<br/>');
            }
            $upgradeArr = array_unique($upgradeArr);
            $upgradeStr = implode('<br/>', $upgradeArr); // 升级提示需要覆盖哪些文件

            $introArr = explode('<br/>', $introStr);
            $introStr = '更新日志：';
            foreach ($introArr as $key => $val) {
                if (empty($val)) {
                    continue;
                }
                $introStr .= "<br/>{$key}、".$val;
            }

            $lastupgrade = $serviceVersionList[count($serviceVersionList) - 1];
            if (!empty($lastupgrade['upgrade_title'])) {
                $introStr .= '<br/>'.$lastupgrade['upgrade_title'];
            }
            $lastupgrade['intro'] = $introStr;
            $lastupgrade['upgrade'] = $upgradeStr; // 升级提示需要覆盖哪些文件
            /*升级公告*/
            if (!empty($lastupgrade['notice'])) {
                $lastupgrade['notice'] .= '<br/>';
            }
            /*--end*/

            return $lastupgrade;
        }
        return '';
    }

    /**
     * 一键更新
     */
    public function OneKeyUpgrade(){
        error_reporting(0);//关闭所有错误报告
        $allow_url_fopen = ini_get('allow_url_fopen');
        if(!$allow_url_fopen)        
            return "请开启 php.ini allow_url_fopen = 1";
               
        if (!extension_loaded('zip')) 
            return "请开启 php.ini 中的php-zip扩展";

        $serviceVersionList = file_get_contents($this->upgrade_url);
        $serviceVersionList = json_decode($serviceVersionList,true);
        if(empty($serviceVersionList))
            return "没找到升级信息";
        
        clearstatcache(); // 清除文件夹权限缓存
        /*$quanxuan = substr(base_convert(@fileperms($this->data_path),10,8),-4);
        if(!in_array($quanxuan,array('0777','0755','0666','0662','0622','0222')))
            return "网站根目录不可写，无法升级.";*/
        if(!is_writeable($this->version_txt_path))         
            return '文件'.$this->version_txt_path.' 不可写，不能升级!!!';
        /*最新更新版本信息*/
        $lastServiceVersion = $serviceVersionList[count($serviceVersionList) - 1];
        /*--end*/
        /*批量下载更新包*/
        $upgradeArr = array(); // 更新的文件列表
        $sqlfileArr = array(); // 更新SQL文件列表
        $folderName = $lastServiceVersion['key_num'];
        foreach ($serviceVersionList as $key => $val) {
            // 下载更新包
            $result = $this->downloadFile($val['down_url'], $val['file_md5']);
            if($result != 1) return $result;

            /*第一个循环执行的业务*/
            if ($key == 0) {
                /*解压到最后一个更新包的文件夹*/
                $lastDownFileName = explode('/', $lastServiceVersion['down_url']);    
                $lastDownFileName = end($lastDownFileName);
                $folderName = str_replace(".zip", "", $lastDownFileName);  // 文件夹
                /*--end*/

                /*解压之前，删除已重复的文件夹*/
                delFile($this->data_path.'backup'.DS.$folderName);
                /*--end*/
            }
            /*--end*/

            $downFileName = explode('/', $val['down_url']);    
            $downFileName = end($downFileName);

            /*解压文件*/
            $zip = new \ZipArchive();//新建一个ZipArchive的对象
            if($zip->open($this->data_path.'backup'.DS.$downFileName) != true)
                return "升级包读取失败!";
            $zip->extractTo($this->data_path.'backup'.DS.$folderName.DS);//假设解压缩到在当前路径下backup文件夹内
            $zip->close();//关闭处理的zip文件
            /*--end*/
            
            if(!file_exists($this->data_path.'backup'.DS.$folderName.DS.'www'.DS.'data'.DS.'conf'.DS.'version.txt'))
                return "缺少version.txt文件,请联系客服";    

            if(file_exists($this->data_path.'backup'.DS.$folderName.DS.'www'.DS.'application'.DS.'database.php'))
                return "不得修改数据库配置文件,请联系客服";

            /*更新的文件列表*/
            $upgrade = !empty($val['upgrade']) ? $val['upgrade'] : array();
            $upgradeArr = array_merge($upgradeArr, $upgrade);
            /*--end*/

            /*更新的SQL文件列表*/
            $sql_file = !empty($val['sql_file']) ? $val['sql_file'] : array();
            $sqlfileArr = array_merge($sqlfileArr, $val['sql_file']);
            /*--end*/
        }
        /*--end*/

        /*将多个更新包重新组建一个新的完全更新包*/
        $upgradeArr = array_unique($upgradeArr); // 移除文件列表里重复的文件
        $sqlfileArr = array_unique($sqlfileArr); // 移除文件列表里重复的文件
        $serviceVersion = $lastServiceVersion;
        $serviceVersion['upgrade'] = $upgradeArr;
        $serviceVersion['sql_file'] = $sqlfileArr;
        /*--end*/

        /*覆盖之前，备份原文件*/
        $upgrade = $serviceVersion['upgrade'];
        if (!empty($upgrade) && is_array($upgrade)) {
            foreach ($upgrade as $key => $val) {
                $source_file = $this->app_path.$val;
                if (file_exists($source_file)) {
                    $destination_file = $this->data_path.'backup'.DS.$this->curent_version.'_www'.DS.$val;
                    tp_mkdir(dirname($destination_file));
                    copy($source_file, $destination_file);
                }
            }
        }
        /*--end*/

        // 递归复制文件夹            
        recurse_copy($this->data_path.'backup'.DS.$folderName.DS.'www', rtrim($this->app_path, DS));
        /*覆盖自定义后台入口文件*/
        $login_php = 'login.php';
        $rootLoginFile = $this->data_path.'backup'.DS.$folderName.DS.'www'.DS.$login_php;
        if (file_exists($rootLoginFile) && is_writable($rootLoginFile)) {
            $adminbasefile = tpCache('web.web_adminbasefile');
            $adminbasefile = trim($adminbasefile, '/');
            if($login_php != $adminbasefile) {
                @copy($rootLoginFile, $this->app_path.$adminbasefile);
                @unlink($this->app_path.$login_php);
            }
        }
        /*--end*/
        /*升级的 sql文件*/
        if(!empty($serviceVersion['sql_file']))
        {
            foreach($serviceVersion['sql_file'] as $key => $val)
            {
                //读取数据文件
                $sqlpath = $this->data_path.'backup'.DS.$folderName.DS.'sql'.DS.trim($val);
                $execute_sql = file_get_contents($sqlpath);
                $sqlFormat = $this->sql_split($execute_sql, PREFIX);
                /**
                 * 执行SQL语句
                 */
                $counts = count($sqlFormat);

                for ($i = 0; $i < $counts; $i++) {
                    $sql = trim($sqlFormat[$i]);

                    if (strstr($sql, 'CREATE TABLE')) {
                        Db::execute($sql);
                    } else {
                        if(trim($sql) == '')
                           continue;
                        Db::execute($sql);
                    }
                }
            }
        }
        /*--end*/
        tpCache('system',['system_version'=>$serviceVersion['key_num']]); // 记录版本号
        // 清空缓存
        delFile(rtrim(RUNTIME_PATH, '/'));
        tpCache('global');

        /*删除下载的升级包*/
        @delFile($this->data_path.'backup'.DS.$folderName, true);
        $ziplist = glob($this->data_path.'backup'.DS.'*.zip');
        @array_map('unlink', $ziplist);
        /*--end*/
        
        // 推送回服务器  记录升级成功
        $this->UpgradeLog($serviceVersion['key_num']);
        
        return 1; 
    }

    public function sql_split($sql, $tablepre) {

        if ($tablepre != "ey_")
            $sql = str_replace("ey_", $tablepre, $sql);
              
        $sql = preg_replace("/TYPE=(InnoDB|MyISAM|MEMORY)( DEFAULT CHARSET=[^; ]+)?/", "ENGINE=\\1 DEFAULT CHARSET=utf8", $sql);
        
        $sql = str_replace("\r", "\n", $sql);
        $ret = array();
        $num = 0;
        $queriesarray = explode(";\n", trim($sql));
        unset($sql);
        foreach ($queriesarray as $query) {
            $ret[$num] = '';
            $queries = explode("\n", trim($query));
            $queries = array_filter($queries);
            foreach ($queries as $query) {
                $str1 = substr($query, 0, 1);
                if ($str1 != '#' && $str1 != '-')
                    $ret[$num] .= $query;
            }
            $num++;
        }
        return $ret;
    }
 
    /**     
     * @param type $fileUrl 下载文件地址
     * @param type $md5File 文件MD5 加密值 用于对比下载是否完整
     * @return string 错误或成功提示
     */
    public function downloadFile($fileUrl,$md5File)
    {                    
        $downFileName = explode('/', $fileUrl);    
        $downFileName = end($downFileName);
        $saveDir = $this->data_path.'backup'.DS.$downFileName; // 保存目录
        tp_mkdir(dirname($saveDir));
        if(!file_get_contents($fileUrl, 0, null, 0, 1)){
            return "下载升级包不存在"; // 文件存在直接退出
        }
        $ch = curl_init($fileUrl);            
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
        $file = curl_exec ($ch);
        curl_close ($ch);                                                            
        $fp = fopen($saveDir,'w');
        fwrite($fp, $file);
        fclose($fp);
        if(!eyPreventShell($saveDir) || !file_exists($saveDir) || $md5File != md5_file($saveDir))
        {
            return "下载保存升级包失败，请检查所有目录的权限以及用户组不能为root";
        }
        return 1;
    }            
    
    // 升级记录 log 日志
    public  function UpgradeLog($to_key_num){
        $serial_number = DEFAULT_SERIALNUMBER;

        $constsant_path = APP_PATH.MODULE_NAME.'/conf/constant.php';
        if (file_exists($constsant_path)) {
            require_once($constsant_path);
            defined('SERIALNUMBER') && $serial_number = SERIALNUMBER;
        }
        $mysqlinfo = \think\Db::query("SELECT VERSION() as version");
        $mysql_version  = $mysqlinfo[0]['version'];
        $vaules = array(                
            'domain'=>$_SERVER['HTTP_HOST'], //用户域名                
            'key_num'=>$this->curent_version, // 用户版本号
            'to_key_num'=>$to_key_num, // 用户要升级的版本号                
            'add_time'=>time(), // 升级时间
            'serial_number'=>$serial_number,
            'ip'    => GetHostByName($_SERVER['SERVER_NAME']),
            'phpv'  => phpversion(),
            'mysql_version' => $mysql_version,
            'web_server'    => $_SERVER['SERVER_SOFTWARE'],
        );
        // api_Service_upgradeLog
        $tmp_str = 'L2luZGV4LnBocD9tPWFwaSZjPVNlcnZpY2UmYT11cGdyYWRlTG9nJg==';
        $url = base64_decode($this->service_ey).base64_decode($tmp_str).http_build_query($vaules);
        @file_get_contents($url);
    }
} 
?>