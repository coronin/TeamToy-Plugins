<?php
/***
TeamToy extenstion info block
##name SELF TODO
##folder_name ziwo
##author cail
##email i@cailiang.net
##reversion 1
##desp 瀑布流的形式显示本人接近所有的TODO（参考自 TODO Flow）。
##update_url http://cailab.sinaapp.com/?c=plugin&a=update_package&name=ziwo
##reverison_url http://cailab.sinaapp.com/?c=plugin&a=latest_reversion&name=ziwo
***/
// todo flow
// a flow view of all todos
if( !defined('IN') ) die('bad request');

$plugin_lang = array();
$plugin_lang['zh_cn'] = array
(
    'PL_ZIWO_TITLE' => '我的TODO一览',
    'PL_ZIWO_TODO_TIME' => '最后活动时间 - %s',
    'PL_ZIWO_NO_TODO_NOW' => '暂无TODO',
    'PL_ZIWO_TEST' => ''
);

$plugin_lang['zh_tw'] = array
(
'PL_ZIWO_TITLE' => '我的TODO一覽',
'PL_ZIWO_TODO_TIME' => '最後活動時間- %s',
'PL_ZIWO_NO_TODO_NOW' => '暫無TODO',
'PL_ZIWO_TEST' => ''
);

$plugin_lang['us_en'] = array
(
    'PL_ZIWO_TITLE' => 'Mine TODO',
    'PL_ZIWO_TODO_TIME' => 'Last active at %s',
    'PL_ZIWO_NO_TODO_NOW' => 'No TODO',
    'PL_ZIWO_TEST' => ''
);


plugin_append_lang( $plugin_lang );

add_action( 'UI_NAVLIST_LAST' , 'ziwo_icon' );
function ziwo_icon()
{
    ?><li <?php if( g('c') == 'plugin' && g('a') == 'ziwo' ): ?>class="active"<?php endif; ?>><a href="?c=plugin&a=ziwo" title="<?=__('PL_ZIWO_TITLE')?>" >
    <div><img src="plugin/ziwo/appicon.png"/></div></a>
    </li>
    <?php
}

add_action( 'PLUGIN_ZIWO' , 'ziwo_view' );
function ziwo_view()
{
    $data['top'] = $data['top_title'] = __('PL_ZIWO_TITLE');
    $data['uid'] = intval( uid() );
    return render( $data , 'web' , 'plugin' , 'ziwo' );
}

add_action( 'PLUGIN_ZIWO_ITEM' , 'ziwo_item' );
function ziwo_item()
{
    $params = array();
    $params['uid'] = intval( uid() );
    $params['ord'] = 'asc';
    $params['by'] = 'tid';
    $params['count'] = 100;

    if($content = send_request( 'todo_list' ,  $params , token()  ))
    {
            $data = json_decode($content , 1);
            $data['user'] = get_user_info_by_id( $uid );

            if( isset($data['data']) )
            foreach( $data['data'] as $k => $v )
            {
                if( $v['is_follow'] == 1 ) unset( $data['data'][$k] );
            }
            return render( $data , 'ajax' , 'plugin' , 'ziwo' );
    }
}