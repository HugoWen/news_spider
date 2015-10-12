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
                            case 13 :
                                $ad['template_type_text'] = '信息流-小图';
                                break;
                            case 14 :
                                $ad['template_type_text'] = '信息流-大图';
                                break;
                            default:
                                $ad['template_type_text'] = $ad['template_type'];
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


