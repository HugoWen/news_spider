<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


/**
 * Class SohuController
 *
 * @Route("/sohu")
 * @package AppBundle\Controller
 */
class SohuController extends Controller
{
    /**
     * @Route("/", name="sohu_index")
     * @Template()
     */
    public function indexAction()
    {
        $news = array();

        for ($i = 1; $i <= 10; $i++) {
            $index_url = $this->container->get('sohu')->sohuApiUrl(1, $i);
            //$index_url = 'http://api.k.sohu.com/api/channel/v5/news.go?channelId=1&num=20&page=1&picScale=11&groupPic=1&supportTV=1&imgTag=1&supportSpecial=1&supportLive=1&showSdkAd=1&rt=json&from=channel&pull=0&mode=0&action=0&cdma_lng=121.425894&cdma_lat=31.131091&net=wifi&p1=NjA1MzE5MTUxMjI5NTM4NzE2MQ==&pid=-1&apiVersion=30&sid=10&buildCode=8&u=1&bid=Y29tLnNvaHUubmV3c3BhcGVy';
            $news[$i] = json_decode($this->container->get('sohu')->curlGetData($index_url), true);
        }

        $ads = array();

        foreach ($news as $page_id => $n) {
            if (isset($n['articles'])) {
                foreach ($n['articles'] as $article) {
                    //判断广告
                    if (isset($article['adType']) && isset($article['data']['adid'])) {
                        $ad['id'] = $article['data']['adid'];
                        $ad['text'] = $article['data']['resource']['text'] ?: $article['data']['resource1']['adcode'] ?: $article['data']['resource2']['adcode'];
                        $ad['template_type'] = $article['templateType'];
                        switch ($ad['template_type']) {
                            case 12 :
                                $ad['template_type_text'] = '信息流-小图';
                                break;
                            case 14 :
                                $ad['template_type_text'] = '信息流-大图';
                                break;
                            default:
                                break;
                        }
                        $ad['pic'] = $article['data']['resource']['adcode'];
                        $ad['link'] = $article['data']['resource']['click'];

                        $ad['page'] = $page_id;

                        $ads[] = $ad;
                    }
                }
            }
        }
        
        return array('ads' => $ads);
    }
}


