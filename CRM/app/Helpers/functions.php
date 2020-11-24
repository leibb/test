<?php

use App\Helpers\AgeHelper;
use Illuminate\Support\Facades\DB;
// 引入鉴权类
use Grafika\Grafika;
use Qiniu\Auth;
// 引入上传类
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;

/**
 * sql日志
 * @return mixed
 */
function sqlLog()
{
    return DB::getQueryLog();
}


function str_hide_replace($str, $start, $len)
{

    return substr_replace($str, str_repeat('*', $len), $start, $len);
}


function getAgeByBirthday($that)
{
    return AgeHelper::howOld($that->getBirthday());
}


function activity_payment_url(\App\Biz\ActivityBiz $activityBiz)
{
    return url("/payment/activity", ['id' => $activityBiz->getId()]);
}

function urlsafe_b64decode($string)
{
    if (empty($string)) {
        return '';
    }

    $data = str_replace(['-', '_'], ['+', '/'], $string);

    $mod4 = strlen($data) % 4;
    if ($mod4) {
        $data .= substr('====', $mod4);
    }

    return base64_decode($data);

}


function idCardNumber2Birthday($numberString)
{
    $year = substr($numberString, 6, 4);
    $m = substr($numberString, 10, 2);
    $d = substr($numberString, 12, 2);
    return "$year-$m-$d";
}


function findUserByUserRedPacketBiz(\App\Biz\UserRedPacketBiz $biz)
{
    if ($biz->getFormUserId()) {
        $mapper = \App\Mapper\UserInfoMapper::findByUid($biz->getFormUserId());
        return [
            'id' => $mapper->id,
            'user_id' => $mapper->user_id,
            'mobile' => str_hide_replace($mapper->mobile, 3, 4),
            'avatar' => $mapper->avatar,
            'nickname' => $mapper->nickname
        ];
    }
    return null;
}

function make_seed()
{
    list($usec, $sec) = explode(' ', microtime());
    return (float)$sec + ((float)$usec * 100000);
}

/**
 * 九个里面平均下来差不多一共10元-15元（因为是前期要给点力）。
 * 有一个5-10的，
 * 有俩个2-5的
 * 剩下六个几毛的。
 * @param int $totalAmount 红包金额
 * @param int $totalPeopleNum 红包个数
 * @return array|float|int
 */
function generateAlgorithm2($totalAmount, $totalPeopleNum)
{
    mt_srand(make_seed());
    $max = mt_rand(500, 1000);
    $mid1 = mt_rand(200, 500);
    $mid2 = mt_rand(200, 500);
    $rands = [
        $max, $mid1, $mid2
    ];

    $array = generateAmountPeopleNum(mt_rand(60, 100), $totalPeopleNum - 3);
    $array = array_merge($array, $rands);
    foreach ($array as &$item) {
        $item = abs($item);
    }
    shuffle($array);
    return $array;
}


/**
 * 9个红包有一个过5元的，一个过2元的，一个过1元的，其他几毛
 * @param int $totalAmount 红包金额
 * @param int $totalPeopleNum 红包个数
 * @return array|float|int
 */
function generateAlgorithm1($totalAmount, $totalPeopleNum)
{
    mt_srand(make_seed());
    $base = mt_rand(800, 940);
    $max = mt_rand(500, $base - 200 - 100);
    $mid = mt_rand(200, $base - $max - 100);
    $min = mt_rand(100, $base - $max - $mid);
    $rands = [
        $max,
        $mid,
        $min
    ];
    $array = generateAmountPeopleNum($totalAmount - array_sum($rands), $totalPeopleNum - 3);
    $array = array_merge($array, $rands);
    foreach ($array as &$item) {
        $item = abs($item);
    }
    shuffle($array);
    return $array;
}


/**
 * 生成红包金额
 * @param int $totalAmount 红包金额，单位分
 * @param int $totalPeopleNum 红包个数
 * @return array|float|int
 */
function generateAmountPeopleNum($totalAmount, $totalPeopleNum)
{
    mt_srand(make_seed());
    $randomList = [];
    $totalRandom = 0;

    while ($totalPeopleNum > count($randomList)) {
        $totalRandom += ($random = mt_rand(1, $totalAmount));
        if (array_search($random, $randomList) == false) {
            //非重复切割添加到集合
            $randomList[] = $random;
        }
    }

    $amountList = [];
    foreach ($randomList as $random) {
        $tmp = $totalAmount * ($random / $totalRandom);
        $amountList[] = round($tmp) <= 0 ? ceil($tmp) : round($tmp);
    }
    array_pop($amountList);
    $amountList[] = $totalAmount - array_sum($amountList);
    if (array_search(0, $amountList) !== false) {
        return call_user_func(__FUNCTION__, $totalAmount, $totalPeopleNum);
    }
    return $amountList;
}

/**
 * base64编码
 * @param $string
 * @return mixed|string
 */
function urlsafe_b64encode($string)
{

    $data = base64_encode($string);

    $data = str_replace(['+', '/', '='], ['-', '_', ''], $data);

    return $data;

}


/**
 * 对key和value到二维数组中去
 *
 * $all = [ [ '11' => '11', ], [ '22' => '22', ] ];
 * $a = array_addcolumn($all, 'aa', 111);
 * [ [ '11' => '11', 'aa'=>111], [ '22' => '22', 'aa'=>111] ];
 *
 * @param $array
 * @param $key
 * @param null $value
 * @return array
 */
function array_addcolumn($array, $key, $value = null)
{
    return array_map(function ($item) use ($key, $value) {
        if (!isset($item[$key])) {
            $item[$key] = $value;
        }
        return $item;
    }, $array);
}

/**
 * 将二位数组的指定key新数组的二位数足的key
 *
 * @param $array
 * @param $key
 * @return array
 */
function array_byKey($array, $key)
{
    $res = [];
    foreach ($array as $k => $value) {
        $res[$value[$key]] = $value;
    }
    return $res;
}


/**
 *
 * $all = [['a11' => '11',], ['a22' => '22',]];
 * $all1 = [['a33' => '11',], ['a33' => '33',]];
 * $a = array_merge_deep($all, $all1);
 * print_r($a);
 *
 * @param $array
 * @param $array2
 * @return mixed
 */
function array_merge_double($array, $array2)
{
    foreach ($array as $key => &$value) {
        if (is_array($value) && isset($array2[$key]) && is_array($array2[$key])) {
            $value = array_merge($value, $array2[$key]);
        }
    };
    unset($value);
    return $array;
}


/**
 * @param $rebateStr
 * @return array
 */
function parse_rebate($rebateStr)
{
    parse_str(str_replace([':', ';'], ['=', '&'], $rebateStr), $rebate);
    return $rebate;
}


/**
 * 获取苹果内购支付情况
 * @param $encodeStr
 * @param int $sandboxStatus
 * @return array
 */
function get_apple_paystatus($encodeStr, $sandboxStatus = 0)
{
    $formalityCurl = "https://buy.itunes.apple.com/verifyReceipt";
    $sandboxCurl = "https://sandbox.itunes.apple.com/verifyReceipt";

    $ch = curl_init();
    $data['receipt-data'] = $encodeStr;
    $encodeStr = json_encode($data);
    $url = $sandboxStatus ? ($formalityCurl) : ($sandboxCurl);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // post数据
    curl_setopt($ch, CURLOPT_POST, 0);
    // post的变量
    curl_setopt($ch, CURLOPT_POSTFIELDS, $encodeStr);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $output = curl_exec($ch);
    curl_close($ch);
    $resut = (Array)json_decode($output, true);
    return $resut;

}


/**
 * 数组转为树
 * @param $list
 * @param string $pk
 * @param string $pid
 * @param string $child
 * @param int $root
 * @return array
 */
function arrayToTree($list, $pk = 'id', $pid = 'pid', $child = 'children', $root = 0)
{
    $tree = [];
    if (is_array($list)) {
        $refer = [];
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] = &$list[$key];
        }

        foreach ($list as $key => $data) {
            // 判断是否存在parent
            $parentId = $data[$pid];

            if ($root == $parentId) {
                $tree[] = &$list[$key];
            } else {
                if (isset($refer[$parentId])) {
                    $parent = &$refer[$parentId];
                    $parent[$child][] = &$list[$key];
                }
            }
        }
    }

    return $tree;
}


/**
 * 生成随机 伪手机号码  中间四位字母
 * @param string $start 开始3位号码
 * @return string
 */
function createRandMobile($start = '')
{
    $list1 = [
        '130', '131', '132', '133', '134', '135', '136', '137', '138', '139',
        '145', '147',
        '150', '151', '152', '153', '155', '156', '157', '158', '159',
        '166',
        '173', '175', '176', '177', '178',
        '180', '181', '182', '183', '184', '185', '186', '187', '188', '189',
        '198', '199'
    ];
    $list2 = 'abcdefghijklmnopqrstuvwxyz';

    if (empty($start)) {
        $start = $list1[mt_rand(0, count($list1) - 1)];
    }
    $body = '';
    for ($i = 0; $i < 4; $i++) {
        $body .= substr($list2, mt_rand(0, strlen($list2)), 1);
    }
    $end = mt_rand(1000, 9999);

    $mobile = $start . $body . $end;
    return $mobile;
}

/**
 * 生成中文昵称
 * @return string
 */
function createChineseNickname()
{
    $nicheng_tou = array('快乐的', '冷静的', '醉熏的', '潇洒的', '糊涂的', '积极的', '冷酷的', '深情的', '粗暴的', '温柔的', '可爱的', '愉快的', '义气的', '认真的', '威武的', '帅气的', '传统的', '潇洒的', '漂亮的', '自然的', '专一的', '听话的', '昏睡的', '狂野的', '等待的', '搞怪的', '幽默的', '魁梧的', '活泼的', '开心的', '高兴的', '超帅的', '留胡子的', '坦率的', '直率的', '轻松的', '痴情的', '完美的', '精明的', '无聊的', '有魅力的', '丰富的', '繁荣的', '饱满的', '炙热的', '暴躁的', '碧蓝的', '俊逸的', '英勇的', '健忘的', '故意的', '无心的', '土豪的', '朴实的', '兴奋的', '幸福的', '淡定的', '不安的', '阔达的', '孤独的', '独特的', '疯狂的', '时尚的', '落后的', '风趣的', '忧伤的', '大胆的', '爱笑的', '矮小的', '健康的', '合适的', '玩命的', '沉默的', '斯文的', '香蕉', '苹果', '鲤鱼', '鳗鱼', '任性的', '细心的', '粗心的', '大意的', '甜甜的', '酷酷的', '健壮的', '英俊的', '霸气的', '阳光的', '默默的', '大力的', '孝顺的', '忧虑的', '着急的', '紧张的', '善良的', '凶狠的', '害怕的', '重要的', '危机的', '欢喜的', '欣慰的', '满意的', '跳跃的', '诚心的', '称心的', '如意的', '怡然的', '娇气的', '无奈的', '无语的', '激动的', '愤怒的', '美好的', '感动的', '激情的', '激昂的', '震动的', '虚拟的', '超级的', '寒冷的', '精明的', '明理的', '犹豫的', '忧郁的', '寂寞的', '奋斗的', '勤奋的', '现代的', '过时的', '稳重的', '热情的', '含蓄的', '开放的', '无辜的', '多情的', '纯真的', '拉长的', '热心的', '从容的', '体贴的', '风中的', '曾经的', '追寻的', '儒雅的', '优雅的', '开朗的', '外向的', '内向的', '清爽的', '文艺的', '长情的', '平常的', '单身的', '伶俐的', '高大的', '懦弱的', '柔弱的', '爱笑的', '乐观的', '耍酷的', '酷炫的', '神勇的', '年轻的', '唠叨的', '瘦瘦的', '无情的', '包容的', '顺心的', '畅快的', '舒适的', '靓丽的', '负责的', '背后的', '简单的', '谦让的', '彩色的', '缥缈的', '欢呼的', '生动的', '复杂的', '慈祥的', '仁爱的', '魔幻的', '虚幻的', '淡然的', '受伤的', '雪白的', '高高的', '糟糕的', '顺利的', '闪闪的', '羞涩的', '缓慢的', '迅速的', '优秀的', '聪明的', '含糊的', '俏皮的', '淡淡的', '坚强的', '平淡的', '欣喜的', '能干的', '灵巧的', '友好的', '机智的', '机灵的', '正直的', '谨慎的', '俭朴的', '殷勤的', '虚心的', '辛勤的', '自觉的', '无私的', '无限的', '踏实的', '老实的', '现实的', '可靠的', '务实的', '拼搏的', '个性的', '粗犷的', '活力的', '成就的', '勤劳的', '单纯的', '落寞的', '朴素的', '悲凉的', '忧心的', '洁净的', '清秀的', '自由的', '小巧的', '单薄的', '贪玩的', '刻苦的', '干净的', '壮观的', '和谐的', '文静的', '调皮的', '害羞的', '安详的', '自信的', '端庄的', '坚定的', '美满的', '舒心的', '温暖的', '专注的', '勤恳的', '美丽的', '腼腆的', '优美的', '甜美的', '甜蜜的', '整齐的', '动人的', '典雅的', '尊敬的', '舒服的', '妩媚的', '秀丽的', '喜悦的', '甜美的', '彪壮的', '强健的', '大方的', '俊秀的', '聪慧的', '迷人的', '陶醉的', '悦耳的', '动听的', '明亮的', '结实的', '魁梧的', '标致的', '清脆的', '敏感的', '光亮的', '大气的', '老迟到的', '知性的', '冷傲的', '呆萌的', '野性的', '隐形的', '笑点低的', '微笑的', '笨笨的', '难过的', '沉静的', '火星上的', '失眠的', '安静的', '纯情的', '要减肥的', '迷路的', '烂漫的', '哭泣的', '贤惠的', '苗条的', '温婉的', '发嗲的', '会撒娇的', '贪玩的', '执着的', '眯眯眼的', '花痴的', '想人陪的', '眼睛大的', '高贵的', '傲娇的', '心灵美的', '爱撒娇的', '细腻的', '天真的', '怕黑的', '感性的', '飘逸的', '怕孤独的', '忐忑的', '高挑的', '傻傻的', '冷艳的', '爱听歌的', '还单身的', '怕孤单的', '懵懂的');
    $nicheng_wei = array('嚓茶', '凉面', '便当', '毛豆', '花生', '可乐', '灯泡', '哈密瓜', '野狼', '背包', '眼神', '缘分', '雪碧', '人生', '牛排', '蚂蚁', '飞鸟', '灰狼', '斑马', '汉堡', '悟空', '巨人', '绿茶', '自行车', '保温杯', '大碗', '墨镜', '魔镜', '煎饼', '月饼', '月亮', '星星', '芝麻', '啤酒', '玫瑰', '大叔', '小伙', '哈密瓜，数据线', '太阳', '树叶', '芹菜', '黄蜂', '蜜粉', '蜜蜂', '信封', '西装', '外套', '裙子', '大象', '猫咪', '母鸡', '路灯', '蓝天', '白云', '星月', '彩虹', '微笑', '摩托', '板栗', '高山', '大地', '大树', '电灯胆', '砖头', '楼房', '水池', '鸡翅', '蜻蜓', '红牛', '咖啡', '机器猫', '枕头', '大船', '诺言', '钢笔', '刺猬', '天空', '飞机', '大炮', '冬天', '洋葱', '春天', '夏天', '秋天', '冬日', '航空', '毛衣', '豌豆', '黑米', '玉米', '眼睛', '老鼠', '白羊', '帅哥', '美女', '季节', '鲜花', '服饰', '裙子', '白开水', '秀发', '大山', '火车', '汽车', '歌曲', '舞蹈', '老师', '导师', '方盒', '大米', '麦片', '水杯', '水壶', '手套', '鞋子', '自行车', '鼠标', '手机', '电脑', '书本', '奇迹', '身影', '香烟', '夕阳', '台灯', '宝贝', '未来', '皮带', '钥匙', '心锁', '故事', '花瓣', '滑板', '画笔', '画板', '学姐', '店员', '电源', '饼干', '宝马', '过客', '大白', '时光', '石头', '钻石', '河马', '犀牛', '西牛', '绿草', '抽屉', '柜子', '往事', '寒风', '路人', '橘子', '耳机', '鸵鸟', '朋友', '苗条', '铅笔', '钢笔', '硬币', '热狗', '大侠', '御姐', '萝莉', '毛巾', '期待', '盼望', '白昼', '黑夜', '大门', '黑裤', '钢铁侠', '哑铃', '板凳', '枫叶', '荷花', '乌龟', '仙人掌', '衬衫', '大神', '草丛', '早晨', '心情', '茉莉', '流沙', '蜗牛', '战斗机', '冥王星', '猎豹', '棒球', '篮球', '乐曲', '电话', '网络', '世界', '中心', '鱼', '鸡', '狗', '老虎', '鸭子', '雨', '羽毛', '翅膀', '外套', '火', '丝袜', '书包', '钢笔', '冷风', '八宝粥', '烤鸡', '大雁', '音响', '招牌', '胡萝卜', '冰棍', '帽子', '菠萝', '蛋挞', '香水', '泥猴桃', '吐司', '溪流', '黄豆', '樱桃', '小鸽子', '小蝴蝶', '爆米花', '花卷', '小鸭子', '小海豚', '日记本', '小熊猫', '小懒猪', '小懒虫', '荔枝', '镜子', '曲奇', '金针菇', '小松鼠', '小虾米', '酒窝', '紫菜', '金鱼', '柚子', '果汁', '百褶裙', '项链', '帆布鞋', '火龙果', '奇异果', '煎蛋', '唇彩', '小土豆', '高跟鞋', '戒指', '雪糕', '睫毛', '铃铛', '手链', '香氛', '红酒', '月光', '酸奶', '银耳汤', '咖啡豆', '小蜜蜂', '小蚂蚁', '蜡烛', '棉花糖', '向日葵', '水蜜桃', '小蝴蝶', '小刺猬', '小丸子', '指甲油', '康乃馨', '糖豆', '薯片', '口红', '超短裙', '乌冬面', '冰淇淋', '棒棒糖', '长颈鹿', '豆芽', '发箍', '发卡', '发夹', '发带', '铃铛', '小馒头', '小笼包', '小甜瓜', '冬瓜', '香菇', '小兔子', '含羞草', '短靴', '睫毛膏', '小蘑菇', '跳跳糖', '小白菜', '草莓', '柠檬', '月饼', '百合', '纸鹤', '小天鹅', '云朵', '芒果', '面包', '海燕', '小猫咪', '龙猫', '唇膏', '鞋垫', '羊', '黑猫', '白猫', '万宝路', '金毛', '山水', '音响');
    $tou_num = rand(0, 331);
    $wei_num = rand(0, 325);
    $nicheng = $nicheng_tou[$tou_num] . $nicheng_wei[$wei_num];
    return $nicheng;
}


function http_curl($url)
{
    $content = file_get_contents($url);
    return $content;
}

/**
 * 上传我主良缘会员头像
 * @param $img
 * @return string
 * @throws Exception
 */
function upload_avatar($img)
{
    ob_clean();
    ob_start();
    readfile($img);        //读取图片
    $img = ob_get_contents();    //得到缓冲区中保存的图片
    ob_end_clean();        //清空缓冲区

    // 构建鉴权对象
    $auth = new Auth(config('qiniu.access_key'), config('qiniu.secret_key'));
    // 生成上传 Token
    $token = $auth->uploadToken(config('qiniu.bucket'));

    // 初始化 UploadManager 对象并进行文件的上传。
    $uploadMgr = new UploadManager();
    $fileName = base64_encode(microtime() . mt_rand(1000, 9999)) . ".jpg";
    list($ret, $err) = $uploadMgr->put($token, $fileName, $img);

    if ($err !== null) {
        throw new \Exception($err);
    }

    return config('qiniu.bucket_url') . "/" . $fileName;
}

/**
 * curl post
 * @param $url
 * @param array $post_data
 * @return bool|string
 */
function curlPost($url, $post_data = [])
{
    $data = json_encode($post_data);
    $headerArray = array("Content-type:application/json;charset='utf-8'", "Accept:application/json");
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headerArray);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    curl_close($curl);
    return json_decode($output, true);
}

/**
 * curl get
 * @param $url
 * @return bool|string
 */
function curlGet($url)
{
    $headerArray = array("Content-type:application/json;charset='utf-8'", "Accept:application/json");
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArray);
    $output = curl_exec($ch);
    curl_close($ch);
    $output = json_decode($output, true);
    return $output;
}


/**
 * 获取当月第一天和最后一天
 * @param $date
 * @return array
 */
function getTheMonth($date)
{
    $first_day = date('Y-m-01', strtotime($date));
    $last_day = date('Y-m-d', strtotime("$first_day +1 month -1 day"));
    return ['first_day' => $first_day, 'last_day' => $last_day];
}

/**
 * 分页
 * @param $data
 * @param $page
 * @param $limit
 * @return array
 */
function paging($data, $page, $limit)
{
    $page = $page ?? 1;
    $limit = $limit ?? 10;
    $start = ($page - 1) * $limit;//偏移量，当前页-1乘以每页显示条数
    $list = array_slice($data, $start, $limit);
    return $list;
}


/**
 * 计算两点地理坐标之间的距离
 * @param float $longitude1 起点经度
 * @param float $latitude1 起点纬度
 * @param float $longitude2 终点经度
 * @param float $latitude2 终点纬度
 * @param Int $unit 单位 1:米 2:公里
 * @param Int $decimal 精度 保留小数位数
 * @return float
 */
function getDistance($longitude1 = 0, $latitude1 = 0, $longitude2 = 0, $latitude2 = 0, $unit = 2, $decimal = 2)
{

    $EARTH_RADIUS = 6370.996; // 地球半径系数
    $PI = 3.1415926;

    $radLat1 = $latitude1 * $PI / 180.0;
    $radLat2 = $latitude2 * $PI / 180.0;

    $radLng1 = $longitude1 * $PI / 180.0;
    $radLng2 = $longitude2 * $PI / 180.0;

    $a = $radLat1 - $radLat2;
    $b = $radLng1 - $radLng2;

    $distance = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2)));
    $distance = $distance * $EARTH_RADIUS * 1000;

    if ($unit == 2) {
        $distance = $distance / 1000;
    }

    return round($distance, $decimal);

}


/**
 * 根据数组中的某个键值大小进行排序
 * @param array $arr 排序数组
 * @param string $keys 键值
 * @param string $orderby 默认正序
 * @return array 排序后数组
 */
function arraySortByKey(array $arr, $keys, $orderby = 'asc')
{
    $keysvalue = $new_array = array();
    foreach ($arr as $k => $v) {
        $keysvalue[$k] = $v[$keys];
    }
    if ($orderby == 'asc') {
        asort($keysvalue);
    } else {
        arsort($keysvalue);
    }
    reset($keysvalue);
    foreach ($keysvalue as $k => $v) {
        $new_array[] = $arr[$k];
    }
    return $new_array;
}

