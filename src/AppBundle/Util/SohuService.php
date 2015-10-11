<?php

namespace AppBundle\Util;

use Symfony\Component\DependencyInjection\ContainerAware;

class SohuService extends containerAware
{
    public function __construct($container)
    {
        $this->container = $container;
    }


    /**
     * 通过curl抓取指定网址内容
     *
     * @param string $url
     * @return mixed
     */
    public function curlGetData($url = '')
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl);
        curl_close($curl);

        return $data;
    }


    public function sohuApiUrl($channel_id = 1, $page = 1)
    {
        $host = 'http://api.k.sohu.com/api/channel/v5/news.go?';

        $p1 = 'NjA1MzE5MTUxMjI5NTM4NzE2MQ==';
        $bid= 'Y29tLnNvaHUubmV3c3BhcGVy';

        return $host.'channelId='.$channel_id.'&num=20&page='.$page.'&picScale=11&groupPic=1&supportTV=1&imgTag=1&supportSpecial=1&supportLive=1&showSdkAd=1&rt=json&from=channel&pull=0&mode=0&action=0&cdma_lng=121.425894&cdma_lat=31.131091&net=wifi&p1='.$p1.'&pid=-1&apiVersion=30&sid=10&buildCode=8&u=1&bid='.$bid;
    }


    /**
     * exportCSV 导出csv文件
     */
    private function exportCSV($filename, $data)
    {
        header('Content-Encoding: UTF-8');
        header('Content-type: text/csv; charset=UTF-8');
        header("Content-Disposition:attachment;filename=".$filename);
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        echo "\xEF\xBB\xBF"; // UTF-8 BOM
        echo $data;
    }


}