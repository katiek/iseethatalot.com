<?php
/*
 * This script should run as svg.php?src=http://...&word=fire
 * to get the svg containing fire with the given image.
 */

header('Content-Type: image/svg+xml');
echo '<?xml version="1.0" standalone="no"?>';

if (isset($_GET['src'])) {
    $image_src = $_GET['src'];
}

if (isset($_GET['word'])) {
    $word = $_GET['word'];
} else {
    $word = '?';
}

// The natural size of the svg
$alot_width = 148;
$alot_height = 103;

$text_size = 12;

// The desired size of the image
$target_width = 400;
$scale_factor = $target_width / $alot_width;
$target_height = $alot_height * $scale_factor;

function get_image_size($url) {
    $ch = curl_init();
    curl_setopt ($ch, CURLOPT_URL, $url);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);

    $contents = curl_exec($ch);
    curl_close($ch);

    $image = imagecreatefromstring($contents);
    
    return array(imagesx($image), imagesy($image));
}

function tile_images($img=NULL, $alot_width, $alot_height) {
    
    if ($img === NULL) {
        return;
    }
    
    $size = get_image_size($img);
    if (!$size) {
        return;
    }
    
    list($original_width, $original_height, $type, $attr) = $size;
    $ratio = $original_height / $original_width;
    
    // Number of times to tile across
    $count_across = 5;
    
    $width = $alot_width / $count_across;
    $height = $width * $ratio;
    
    $count_down = ceil($alot_height / $height);
    for ($i = 0; $i < $count_across * $count_down; $i++) { 
            $x = ($i % $count_across) * $width;
            $y = floor($i / $count_across) * $height;
            $transform = "translate($x, $y)";

        echo "<image width='$width' height='$height' xlink:href='$img' transform='$transform'/>";
    }
}
?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
<svg 
    width="<?php echo $target_width?>" 
    height="<?php echo $target_height + $text_size + 5?>" 
    version="1.1" 
    xmlns="http://www.w3.org/2000/svg" 
    xmlns:xlink="http://www.w3.org/1999/xlink">
    <defs>
        <style type="text/css"><![CDATA[
          text {
            font-family: Arial, Helvetica, sans-serif; 
            font-size: <?php echo $text_size?>px;
          }
          text.title {
            font-weight: bold;
          }
          text.attrib {
            text-anchor: end;
            font-size: <?php echo $text_size - 3?>px;
          }
        ]]></style>
        <filter id="f1" x="0" y="0" width="200%" height="200%">
          <feOffset result="offOut" in="SourceGraphic" dx="0" dy="0" />
          <feComponentTransfer in="offOut" result="colorOut">
             <feFuncR type="discrete" tableValues="1"/>
             <feFuncG type="discrete" tableValues="1"/>
             <feFuncB type="discrete" tableValues="1"/>
          </feComponentTransfer>
          <feGaussianBlur result="blurOut" in="colorOut" stdDeviation="0.6" />
          <feBlend in="SourceGraphic" in2="blurOut" mode="normal" />
        </filter>

        <clipPath id="alot">
            <path
       d="M 68.78125,0 C 68.389404,0 65.623517,1.8791688 61.1875,5.15625 58.097135,7.4392337 57.295302,7.645363 53.875,7 51.772007,6.603189 50.43743,6.0421145 48.21875,4.59375 45.699557,2.9491998 45.106999,2.711155 43.53125,2.78125 c -1.074882,0.047776 -2.322776,-0.1783879 -3.0625,-0.5625 -1.469413,-0.7630244 -4.242724,-0.8579289 -4.5,-0.15625 -0.142714,0.3892016 -0.306194,0.3892016 -0.71875,0 -0.309987,-0.2924487 -1.572047,-0.5 -3.1875,-0.5 -2.34211,0 -2.898491,0.1386642 -4.40625,1.15625 -1.603373,1.0821112 -2.012766,1.1875 -5.25,1.1875 0,0 -3.011031,-0.1705676 -3.46875,0 -0.153994,0.057406 -0.25,1.125 -0.25,1.125 -0.208335,1.041694 -0.375579,1.1381872 -2.46875,1.375 C 14.37156,6.6152347 13.925097,6.7991488 13.75,7.46875 13.561533,8.1894472 13.356538,8.258164 11.9375,8.03125 10.059212,7.730902 6.3530496,5.9277659 4.65625,4.5 2.2285623,2.4572189 1.483111,3.7010859 3.1875,6.9375 c 1.3785421,2.6176998 3.2242905,4.503732 5.34375,5.5 l 1.8125,0.875 -0.21875,1.8125 c -0.124681,0.996458 -0.4811835,1.973435 -0.8125,2.1875 -2.2331915,1.442934 -2.7414121,3.876741 -1.21875,5.8125 l 0.8125,1.03125 -1.03125,1.9375 c -1.1387491,2.177781 -1.2666812,3.09375 -0.4375,3.09375 0.4959247,0 0.4959247,0.219413 0,1.625 -0.3115979,0.883156 -0.5625,1.888847 -0.5625,2.25 0,0.361163 -0.5051441,1.156143 -1.125,1.75 C 4.8800916,35.645919 4.5520251,36.36427 4.34375,38.03125 4.1671425,39.444756 4.2231797,40.297672 4.5,40.46875 4.7782302,40.640708 4.4128307,41.778867 3.4375,43.8125 1.240956,48.392446 -0.01629882,51.507806 0,52.28125 c 0.00159988,0.0805 0.03300204,0.1585 0.0625,0.1875 0.16267845,0.158088 1.021435,-0.588676 1.9375,-1.625 1.0966022,-1.240609 1.7822132,-1.708028 1.96875,-1.40625 0.1554493,0.252082 0.1534392,2.14095 0,4.1875 -0.2421028,3.229365 -0.196664,3.664135 0.3125,3.46875 C 4.7710152,56.905763 4.887288,57.43731 5,60.3125 c 0.074265,1.894262 0.2443915,3.588312 0.40625,3.75 0.1618787,0.161588 0.9229244,-0.419262 1.6875,-1.3125 0.7645858,-0.893235 1.552624,-1.65625 1.75,-1.65625 0.197376,0 0.375,1.926621 0.375,4.28125 0,3.797322 0.3501998,5.264793 0.90625,3.8125 0.221875,-0.579458 -0.09983,6.343583 -0.375,8.15625 -0.1427902,0.940632 -0.4335114,1.349864 -1.15625,1.53125 -1.055975,0.265081 -2.113801,1.923253 -2.125,3.34375 -0.0039,0.492264 -0.4942348,1.631667 -1.125,2.5625 -1.5987665,2.359128 -3.840854,8.582367 -3.90625,10.875 -0.015809,0.55536 1.7941635,0.66036 2.125,0.125 0.1198815,-0.19379 0.5597412,-0.240192 1,-0.125 0.5247128,0.13719 1.1569287,-0.141097 1.84375,-0.78125 0.576299,-0.537261 1.4156204,-1.125814 1.84375,-1.3125 0.9562422,-0.416667 6.519394,-0.401218 9.8125,0.03125 1.750834,0.229984 2.4375,0.194977 2.4375,-0.125 0,-0.244582 0.573391,-0.4375 1.28125,-0.4375 1.209407,0 1.35787,-0.15638 2.5,-2.625 0.668476,-1.444995 1.32213,-2.937828 1.4375,-3.3125 0.230679,-0.749046 1,-0.94285 1,-0.25 0,0.256885 0.366761,0.351639 0.875,0.21875 0.618715,-0.161888 1.422846,0.18483 2.875,1.28125 C 32.515305,89.888838 34.5,90.711849 34.5,90 c 0,-0.212384 -0.353138,-0.481758 -0.78125,-0.59375 -0.678271,-0.177387 -1.141876,-1.4375 -0.53125,-1.4375 0.125244,0 1.398606,1.059344 2.84375,2.34375 2.671714,2.374427 3.336565,2.709087 3.625,1.84375 0.122666,-0.367873 0.970028,-0.288552 3.5,0.375 1.832208,0.480564 3.905312,0.875 4.59375,0.875 0.982571,0 1.185839,0.140735 1,0.625 -0.130205,0.339275 -0.341704,1.519381 -0.46875,2.625 -0.227811,1.982556 -0.199188,2.01784 1.0625,2.5625 1.156627,0.49926 1.315272,0.49276 1.6875,-0.125 1.028238,-1.706369 1.207617,-1.855331 2.25,-1.59375 0.572613,0.143688 1.152127,0.59313 1.28125,1 0.129137,0.40688 0.394114,0.75 0.59375,0.75 0.484522,0 1.9375,-1.446336 1.9375,-1.9375 0,-0.214085 0.331946,-0.40625 0.75,-0.40625 1.227418,0 2.75,0.87274 2.75,1.5625 0,0.35097 0.21189,0.83687 0.46875,1.09375 0.283448,0.28348 0.509333,0.4375 0.71875,0.4375 0.269245,0.003 0.523648,-0.24833 0.875,-0.75 0.450652,-0.64294 0.922865,-0.7625 2.875,-0.6875 1.434352,0.0551 2.742486,-0.10002 3.375,-0.4375 0.828436,-0.44188 1.206749,-0.46943 2.03125,-0.09375 1.406023,0.64055 1.634217,0.271253 0.625,-1.09375 -1.092931,-1.478195 -1.081733,-1.59375 0.3125,-1.59375 1.043573,0 1.15625,-0.120478 1.15625,-1.1875 0,-1.057123 0.09955,-1.141033 0.875,-0.90625 1.157957,0.350775 3.211072,1.587853 3.8125,2.3125 0.863318,1.040225 1.656519,0.719207 1.375,-0.5625 l -0.28125,-1.15625 2.84375,0.8125 C 83.210764,95.096217 85.158638,95.703121 86,96 c 1.651719,0.582757 3,0.684068 3,0.25 0,-0.148789 -0.456047,-0.743394 -1.03125,-1.34375 -1.352965,-1.412198 -0.852317,-1.41864 3.6875,0.09375 1.994109,0.664352 3.725818,1.117891 3.84375,1 0.117916,-0.117892 -0.172463,-0.799004 -0.625,-1.53125 -0.975206,-1.577886 -1.00812,-1.84375 -0.25,-1.84375 0.582587,0 3.235018,1.360046 3.90625,2 0.65145,0.621154 0.929633,0.358006 1.09375,-0.9375 0.145625,-1.149516 0.197012,-1.170841 0.46875,-0.375 0.46229,1.353802 1.78399,1.059073 3.125,-0.6875 1.19579,-1.557487 2.01722,-2.04102 1.59375,-0.9375 -0.1304,0.339775 -0.36608,1.318711 -0.5,2.15625 -0.285,1.782269 0.1755,1.927063 2,0.71875 0.64217,-0.425269 1.625,-0.75 2.1875,-0.75 0.56243,-0.0014 1.26476,-0.272673 1.53125,-0.59375 0.70616,-0.850838 1.00036,-0.732101 1.5625,0.625 0.43821,1.057923 0.41056,1.245223 -0.15625,1.5625 -1.06695,0.597056 -0.73384,1.125 0.71875,1.125 0.90578,0 1.375,0.170276 1.375,0.53125 0,0.762543 8.26812,0.791293 9.84375,0.03125 0.87256,-0.420574 1.26369,-0.436472 1.875,-0.0625 0.64085,0.392071 0.83441,0.347708 1.15625,-0.21875 0.21281,-0.374673 0.8117,-0.6875 1.34375,-0.6875 0.53204,0 1.39702,-0.260175 1.90625,-0.59375 0.86589,-0.567359 0.94966,-0.561846 1.1875,0.1875 0.3767,1.186813 2.05814,1.088159 2.53125,-0.15625 0.2438,-0.641253 0.66654,-1 1.25,-1 0.48696,-0.003 1.24672,-0.100358 1.6875,-0.21875 0.59636,-0.160188 0.91129,0.0378 1.25,0.78125 0.58347,1.284906 1.53996,1.284007 2.125,0 l 0.4375,-1 4.03125,0.28125 c 2.78682,0.189942 4.03124,0.185857 3.96875,-0.625 -0.0486,-0.630666 -0.88298,-1.735896 -2.40625,-3.65625 l -1.5625,-1.96875 1.40625,-0.34375 C 146.34178,87.614215 147,87.293888 147,87.125 c 0,-0.168987 -0.62578,-1.41825 -1.375,-2.78125 -0.7492,-1.363001 -1.3628,-2.727873 -1.375,-3.03125 -0.0122,-0.303278 -1.08825,-1.429678 -2.40625,-2.5 -2.116,-1.718274 -2.2936,-1.948309 -1.5,-2.0625 0.7288,-0.104793 0.847,-0.2845 0.625,-0.96875 -0.15056,-0.464066 -0.40768,-1.822686 -0.5625,-3 -0.15481,-1.177314 -0.66089,-3.103935 -1.125,-4.28125 -1.00353,-2.545814 -1.0009,-2.34375 -0.28125,-2.34375 0.76298,0 0.74471,-0.359879 -0.1875,-2.8125 -0.92901,-2.444322 -0.90417,-2.21875 -0.1875,-2.21875 0.32108,0 0.59375,-0.166063 0.59375,-0.34375 0,-0.904034 -2.04399,-5.085021 -3.28125,-6.75 -0.7751,-1.043024 -1.40625,-2.181222 -1.40625,-2.5 0,-1.297105 -2.34706,-5.19921 -4.625,-7.65625 l -1.375,-1.4375 2.625,0 c 1.43484,0 2.59375,-0.120049 2.59375,-0.25 0,-0.716307 -10.01349,-9.962337 -11.78125,-10.875 -1.11368,-0.574988 -3.5323,-1.970365 -5.375,-3.125 -3.10643,-1.946478 -4.625,-2.553347 -4.625,-1.8125 0,0.4001 -10.31252,0.134327 -12.0625,-0.3125 -0.804933,-0.205525 -1.52018,-0.711987 -1.75,-1.21875 -0.762496,-1.681367 -2.833325,-2.281452 -9.8125,-2.875 -3.603161,-0.306437 -7.469928,-0.5595 -8.59375,-0.5625 -1.123823,-0.0028 -2.03125,-0.152584 -2.03125,-0.34375 0,-0.524143 -0.995083,-0.817657 -5.25,-1.59375 -2.134497,-0.389331 -3.95379,-0.892577 -4.03125,-1.125 -0.07748,-0.232413 -0.729762,-0.4375 -1.46875,-0.4375 -1.216736,0 -1.430538,-0.171543 -2.09375,-1.71875 -0.402803,-0.939701 -0.913754,-1.818881 -1.15625,-1.96875 -0.301248,-0.186187 -0.139855,-0.659681 0.5,-1.5 0.515068,-0.676441 1.057113,-1.652349 1.21875,-2.1875 C 65.59915,9.996099 65.96715,9.017193 66.25,8.375 66.532851,7.732815 67.024426,6.4320297 67.34375,5.46875 67.66309,4.5054801 68.193971,3.045852 68.53125,2.25 69.24037,0.576772 69.319909,0 68.78125,0 z"
       transform="translate(0,2.3621956)"
       id="rect4171"
       style="fill:#ffff00;fill-opacity:1" />
        </clipPath>
    </defs>
    <?php
        $text_offset_y = $target_height + $text_size;
        
        $text_offset_x = 5;
        if ($word) {
	        echo "<text class='title' transform='translate($text_offset_x, $text_offset_y)'>alot of $word</text>";
        }
        $text_offset_x = $target_width - 5;
        echo "<text class='attrib' transform='translate($text_offset_x, $text_offset_y)'>ISEETHATALOT.COM</text>";
    ?>
    
    <g transform="scale(<?php echo $scale_factor ?>)">
        <g clip-path="url(#alot)">
            <?php tile_images($image_src, $alot_width, $alot_height); ?>
        </g>
        <g id="foreground" filter="url(#f1)">
            <path
               d="m 24.40678,17.285775 c -0.547055,0.415701 -0.694732,0.907248 -0.930752,1.398294 -0.05886,0.496006 -0.122872,0.338197 -0.209647,0.629587 -0.0848,0.326518 0.06536,0.604979 -0.09755,1.424153 0.08554,0.940846 -0.008,1.983755 0.618897,2.725544 0.797278,0.44798 1.376959,-0.645876 1.935098,-1.030309 0.366028,-0.344827 1.248378,-0.478338 0.961055,-1.168851 -0.505189,-1.180409 -1.655269,-2.117685 -1.666695,-3.489232 -0.06615,-0.277111 -0.30347,-0.535843 -0.610399,-0.489186 z"
               id="path4127"
               style="fill:#ffffff;fill-opacity:1" />
            <path
               d="m 67.312272,3.9522441 c -2.770307,1.6893948 -5.347076,3.7121368 -8.056415,5.467607 -0.502891,0.4169216 -1.197651,0.3444765 -1.740289,0.6407069 -0.644081,0.642616 0.319347,1.421193 0.987031,1.372316 0.469161,0.73972 -0.07795,1.699304 -0.372302,2.406966 -0.250657,0.606108 -0.762581,1.683335 0.02165,2.103926 0.979117,0.252813 1.833589,-0.791286 2.835545,-0.329007 0.657422,0.339147 1.569487,0.450939 2.047654,-0.246763 1.46426,-1.642088 2.482929,-3.615734 3.108278,-5.7230303 0.57248,-1.7970574 1.45276,-3.545958 1.666695,-5.4286496 -0.0092,-0.2379837 -0.295963,-0.4070322 -0.497843,-0.264072 z"
               id="path4123"
               style="fill:#ffffff;fill-opacity:1" />
            <path
               d="M 3.5309409,6.8643654 C 3.2814874,6.6401807 3.1337172,6.4252854 2.8974529,6.8539662 2.5075587,7.2438596 4.2731817,10.688035 5.8409276,12.199102 c 1.5718658,1.515046 4.5272704,3.094179 4.9689294,2.703185 0.107034,-0.09479 0.376206,-1.214907 0.376206,-2.159572 0,-0.944676 0.1377,-1.944978 -0.0223,-1.945388 C 10.587903,10.796327 5.3744186,8.2435814 4.3617757,7.4470457 3.8635488,7.0551324 3.7058093,6.8627256 3.5309209,6.8643954 z"
               id="path4121"
               style="fill:#ffffff;fill-opacity:1" />
            <path
               d="m 60.208255,15.961075 c -1.726998,0.0658 -3.126969,1.522326 -3.723007,3.052002 -0.673053,1.792328 0.454173,3.65834 1.939426,4.632104 1.224129,0.976143 2.94635,0.598779 4.117388,-0.268472 1.403585,-0.963274 2.349505,-2.795359 2.029902,-4.510822 -0.588238,-1.757 -2.538965,-2.967568 -4.363709,-2.904812 z"
               id="path4119"
               style="fill:#ffffff;fill-opacity:1" />
            <path
               d="m 10.436853,20.043397 c -1.0645896,-0.0379 -1.8895948,0.742529 -2.4329489,1.580102 0.90506,0.125611 1.9609398,0.0227 2.7489669,0.510835 0.16108,0.685063 0.09519,1.60469 -0.45456,2.095267 -0.7656083,0.418721 -1.7287144,0.305079 -2.5455001,0.09959 0.9649559,1.279223 2.8292521,2.023842 4.2901251,1.17751 1.126595,-0.603409 1.889004,-1.987205 1.34634,-3.238149 -0.45291,-1.242415 -1.534988,-2.35422 -2.952423,-2.225138 z"
               id="path4113"
               style="fill:#ffffff;fill-opacity:1" />
            <path
               d="m 7.9779259,21.536925 c -0.8761319,0.956224 -0.7264718,2.585793 0.331828,3.355521 0.6514967,0.812984 0.061096,-0.705382 0.070195,-1.141572 -0.135721,-0.921917 0.073595,-1.868763 0.1694488,-2.78972 -0.1904874,0.191927 -0.3809648,0.383844 -0.5714421,0.575771 z"
               id="path4111"
               style="fill:#ffffff;fill-opacity:1" />
            <path
               d="M 9.3589143,22.138664 C 8.0404017,22.33681 8.5979148,24.730537 9.8351028,24.376791 10.711314,24.023025 10.832926,22.653349 9.9303963,22.251216 9.7539273,22.164322 9.556091,22.115495 9.3589741,22.138664 z"
               id="path4109"
               style="fill:#ffffff;fill-opacity:1" />
            <path
               d="m 61.054559,101.9251 c -0.256863,-0.25678 -0.467041,-0.75415 -0.467041,-1.10512 0,-0.68976 -1.515315,-1.541399 -2.742739,-1.541399 -0.418056,0 -0.760102,0.175088 -0.760102,0.389173 0,0.491166 -1.435273,1.946066 -1.919797,1.946066 -0.199637,0 -0.468622,-0.33287 -0.597759,-0.73975 -0.129125,-0.40687 -0.703264,-0.85734 -1.27588,-1.00103 -1.042387,-0.261582 -1.233504,-0.128291 -2.261746,1.57819 -0.372231,0.61766 -0.534367,0.63026 -1.691,0.131 -1.261694,-0.54467 -1.276115,-0.57986 -1.048302,-2.562432 0.127047,-1.105624 0.337529,-2.287844 0.467734,-2.62712 0.18584,-0.484267 -0.03239,-0.616958 -1.014971,-0.616958 -0.688441,0 -2.750814,-0.393174 -4.583032,-0.873741 -2.529982,-0.663554 -3.370157,-0.757148 -3.492825,-0.389173 -0.288435,0.865341 -0.958381,0.523764 -3.630108,-1.850674 -1.44515,-1.284412 -2.730034,-2.335241 -2.85528,-2.335241 -0.610628,0 -0.127587,1.266614 0.550687,1.444002 0.428114,0.111992 0.778401,0.377274 0.778401,0.58956 0,0.711951 -1.997955,-0.101593 -4.044521,-1.646688 -1.45216,-1.096425 -2.260596,-1.4613 -2.879315,-1.299511 -0.50824,0.132991 -0.86026,0.047 -0.86026,-0.209586 0,-0.692853 -0.781922,-0.502566 -1.012602,0.246483 -0.115369,0.374575 -0.75671,1.863273 -1.425188,3.308274 -1.142129,2.468632 -1.293045,2.627121 -2.502457,2.627121 -0.707863,0 -1.287031,0.200087 -1.287031,0.44467 0,0.319978 -0.681682,0.355076 -2.432519,0.125191 -3.293121,-0.43257 -8.8711809,-0.452869 -9.8274374,-0.036 -0.4281216,0.186687 -1.2499272,0.778947 -1.826229,1.31611 -0.6868144,0.640256 -1.3236321,0.904638 -1.8483473,0.767448 -0.4402608,-0.115193 -0.8985704,-0.051 -1.0184525,0.14329 -0.330848,0.535363 -2.1199993,0.421571 -2.1041904,-0.133791 0.065396,-2.292744 2.2859983,-8.50032 3.8847724,-10.859559 0.6307681,-0.930737 1.1500536,-2.095057 1.1539534,-2.587324 0.011199,-1.420603 1.0610996,-3.07869 2.1170696,-3.343771 0.722742,-0.181388 1.0183124,-0.564562 1.1610932,-1.505098 0.275181,-1.812676 0.575052,-8.749603 0.353176,-8.170142 -0.5560628,1.452201 -0.89982,0 -0.89982,-3.794741 0,-2.35474 -0.1615093,-4.281308 -0.3588862,-4.281308 -0.1973769,0 -0.9844347,0.73085 -1.749024,1.624089 C 6.3400045,65.988852 5.5820049,66.587511 5.4201255,66.425922 5.2582662,66.264333 5.0650791,64.582248 4.990814,62.687877 4.8780915,59.812673 4.7588594,59.28091 4.2690919,59.468797 3.7599257,59.664183 3.7192683,59.202315 3.9613723,55.972935 c 0.1534399,-2.04656 0.15148,-3.927332 -0.004,-4.179415 C 3.7708349,51.491741 3.1050792,51.978808 2.0084719,53.219423 1.0924027,54.255752 0.20978108,54.974403 0.04708187,54.816314 -0.26793724,54.510335 1.0234472,51.228059 3.4472364,46.174374 4.4225718,44.140733 4.7850876,42.993731 4.5068561,42.821773 4.2300445,42.650694 4.177158,41.814241 4.3537563,40.400738 c 0.2082761,-1.666997 0.526915,-2.405956 1.3968274,-3.239389 0.6198488,-0.59385 1.1270152,-1.375226 1.1270152,-1.736392 0,-0.361165 0.2549331,-1.379246 0.5665324,-2.262405 0.4959271,-1.405585 0.4959271,-1.605751 0,-1.605751 -0.829175,0 -0.6964838,-0.933426 0.4422606,-3.111218 L 8.895185,26.516305 8.0809791,25.481226 C 6.55831,23.545458 7.0664664,21.129013 9.2996782,19.686081 9.6309862,19.472016 10.004051,18.481593 10.128733,17.485131 L 10.355398,15.673395 8.5192,14.810284 C 6.3997206,13.814012 4.5524531,11.905532 3.1739146,9.2878101 1.4695176,6.0513909 2.2137683,4.8232347 4.6414772,6.8660253 c 1.6968075,1.4277626 5.4103808,3.2228207 7.2886868,3.5231697 1.419035,0.226915 1.634112,0.160849 1.822579,-0.5598518 0.175108,-0.6696044 0.620658,-0.8631711 2.467846,-1.0721569 2.093192,-0.2368138 2.270199,-0.3351471 2.478535,-1.3768361 0,0 0.07029,-1.0643973 0.224368,-1.1218034 0.457715,-0.1705784 3.493056,0 3.493056,0 3.23725,0 3.619761,-0.085494 5.22314,-1.1676204 1.507765,-1.0175705 2.075395,-1.1676103 4.417517,-1.1676103 1.615459,0 2.893152,0.1940668 3.203143,0.4864968 0.412556,0.3892235 0.55138,0.3892235 0.694093,0 0.257278,-0.7016621 3.041977,-0.6027289 4.511396,0.1602991 0.739727,0.3841338 1.980627,0.6141281 3.055513,0.5663413 1.575757,-0.070095 2.18428,0.1639189 4.703484,1.8084667 2.21869,1.4483811 3.533236,2.0096328 5.636238,2.4064358 3.420318,0.6453759 4.241583,0.4388 7.331963,-1.8441942 4.436038,-3.2770864 7.181491,-5.1406693 7.573338,-5.1406693 0.538663,0 0.479857,0.5772006 -0.229267,2.2504265 -0.337281,0.7958557 -0.874515,2.2351575 -1.193857,3.1984418 -0.319325,0.9632742 -0.812023,2.2768454 -1.094876,2.9190314 -0.28285,0.642186 -0.646521,1.60546 -0.808172,2.140614 -0.161638,0.535153 -0.715316,1.526466 -1.230387,2.202909 -0.639858,0.840323 -0.796842,1.316211 -0.495593,1.502398 0.242498,0.14987 0.770466,1.041329 1.173271,1.981025 0.663215,1.547214 0.859235,1.708543 2.075976,1.708543 0.738992,0 1.406999,0.190167 1.484479,0.422581 0.07747,0.232424 1.88727,0.74113 4.021777,1.130463 4.254933,0.776107 5.243141,1.078087 5.243141,1.602231 0,0.191157 0.919496,0.349876 2.043325,0.352706 1.123828,0.003 4.991391,0.255852 8.59457,0.562291 6.979206,0.59355 9.03263,1.196959 9.79513,2.878334 0.229821,0.506775 0.946494,1.000102 1.75143,1.205628 1.74999,0.446819 12.06556,0.722211 12.06556,0.322118 0,-0.74085 1.52187,-0.1502 4.62831,1.796297 1.84271,1.154641 4.26159,2.569795 5.37528,3.144776 1.76776,0.912677 11.79186,10.149837 11.79186,10.866148 0,0.129951 -1.17396,0.236284 -2.60881,0.236284 l -2.60882,0 1.35313,1.459511 c 2.27795,2.457052 4.64292,6.354616 4.64292,7.651728 0,0.318778 0.63418,1.433002 1.40927,2.476031 1.23728,1.664986 3.26117,5.867399 3.26117,6.771438 0,0.177688 -0.26272,0.323078 -0.58381,0.323078 -0.71665,0 -0.73422,-0.202187 0.19479,2.242147 0.93222,2.452632 0.95739,2.817507 0.19442,2.817507 -0.71964,0 -0.74311,-0.210585 0.26041,2.335241 0.4641,1.17732 0.9705,3.103888 1.12531,4.281308 0.15482,1.17732 0.40467,2.520328 0.55523,2.984396 0.22201,0.684254 0.10386,0.868141 -0.62495,0.972934 -0.79359,0.114192 -0.61835,0.356976 1.49766,2.075258 1.31802,1.070327 2.40634,2.194251 2.41851,2.49753 0.0122,0.303279 0.63511,1.666686 1.38432,3.029693 0.74922,1.363107 1.36221,2.616522 1.36221,2.78541 0,0.168989 -0.6376,0.469468 -1.41688,0.667755 l -1.41692,0.360575 1.55014,1.954167 c 3.48176,4.3894 3.39614,4.625084 -1.55822,4.287307 l -4.00953,-0.273281 -0.458,1.005231 c -0.58504,1.284013 -1.53068,1.286813 -2.11414,0.01 -0.33871,-0.74335 -0.66018,-0.943936 -1.25656,-0.783747 -0.44077,0.118392 -1.19981,0.217685 -1.68676,0.220585 -0.58347,0 -1.01149,0.337077 -1.25529,0.978333 -0.47311,1.244415 -2.16037,1.358608 -2.53707,0.171789 -0.23784,-0.749349 -0.31423,-0.761948 -1.18014,-0.194587 -0.50923,0.333677 -1.36117,0.606659 -1.89322,0.606659 -0.53205,0 -1.1415,0.306479 -1.3543,0.681053 -0.32185,0.566461 -0.51663,0.601759 -1.15747,0.209786 -0.61131,-0.374075 -0.99776,-0.361876 -1.87033,0.059 -1.57563,0.759947 -9.85691,0.745047 -9.85691,-0.018 0,-0.361075 -0.45642,-0.542963 -1.3622,-0.542963 -1.4526,0 -1.77954,-0.506165 -0.71257,-1.103325 0.56681,-0.317178 0.58561,-0.518164 0.14739,-1.576092 -0.56213,-1.357108 -0.86929,-1.479699 -1.57545,-0.628857 -0.26649,0.321178 -0.94472,0.58496 -1.50716,0.58646 -0.56249,0 -1.54811,0.350476 -2.19029,0.775847 -1.8245,1.208317 -2.28161,1.037829 -1.99661,-0.744449 0.13393,-0.837543 0.35019,-1.800778 0.48059,-2.140654 0.42347,-1.103525 -0.40182,-0.618458 -1.59761,0.939036 -1.34102,1.74658 -2.66499,2.034961 -3.12728,0.681053 -0.271743,-0.795746 -0.313631,-0.760248 -0.459256,0.389274 -0.164116,1.295511 -0.451942,1.533495 -1.103395,0.912337 -0.671236,-0.639956 -3.301833,-1.982664 -3.884422,-1.982664 -0.758124,0 -0.724377,0.245283 0.250837,1.823275 0.452537,0.73225 0.726315,1.427803 0.608398,1.545695 -0.117932,0.117992 -1.845989,-0.329078 -3.840108,-0.993433 -4.539836,-1.512496 -5.049559,-1.528495 -3.696587,-0.116292 0.575206,0.600359 1.045835,1.213318 1.045835,1.362207 0,0.433971 -1.336957,0.313679 -2.988683,-0.269181 -0.841366,-0.29678 -2.801614,-0.899639 -4.356135,-1.339509 l -2.826409,-0.799845 0.251474,1.144922 c 0.281522,1.281712 -0.499956,1.60609 -1.363277,0.565861 -0.601431,-0.724651 -2.640699,-1.971065 -3.798661,-2.321742 -0.775448,-0.234884 -0.875702,-0.128491 -0.875702,0.928637 0,1.067027 -0.124041,1.193819 -1.167619,1.193819 -1.394241,0 -1.412484,0.08999 -0.319548,1.567793 1.009222,1.365007 0.799542,1.747877 -0.606487,1.107227 -0.824505,-0.37568 -1.229735,-0.35938 -2.058175,0.083 -0.632516,0.33758 -1.936434,0.51577 -3.370793,0.46067 -1.952145,-0.075 -2.428594,0.038 -2.879248,0.68105 -0.624634,0.89183 -0.937767,0.95183 -1.585648,0.30398 z m 4.845037,-2.441633 c -0.121049,-0.315479 -0.0084,-0.709552 0.250255,-0.875741 0.258692,-0.166188 0.470339,-0.645856 0.470339,-1.066027 0,-1.008531 2.271221,-1.725482 3.343919,-1.055628 0.880805,0.550063 2.29955,0.603359 2.29955,0.08599 0,-0.352076 -0.781298,-2.34104 -1.072974,-2.731413 -0.07533,-0.100893 -0.297619,0.07399 -0.493972,0.389173 -0.236777,0.379674 -0.281744,0.103793 -0.133502,-0.819344 0.235835,-1.4685 -0.983525,-4.967261 -2.035359,-5.840202 -0.295277,-0.245083 -0.443782,-0.596259 -0.330022,-0.780246 0.113775,-0.184088 -0.248718,-1.4735 -0.805541,-2.865305 -1.151798,-2.879103 -1.1984,-5.511724 -0.109646,-6.193977 0.808006,-0.506266 1.411251,-0.025 0.771116,0.614758 -1.017299,1.01733 -0.211841,4.845969 1.865507,8.867395 0.387026,0.749149 0.902387,2.098757 1.145245,2.998895 0.242873,0.900239 0.743383,1.939268 1.112262,2.309043 0.368892,0.369774 0.671554,0.929436 0.672594,1.243815 0.0014,0.314278 0.665343,0.801145 1.476258,1.081926 0.810903,0.280781 1.992263,0.87924 2.625252,1.329909 l 1.150856,0.819444 0,-0.804845 c 0,-1.133222 0.172568,-1.109724 5.878029,0.800346 2.821049,0.944335 3.392058,0.948435 2.550194,0.018 -0.354278,-0.391473 -0.644126,-0.933336 -0.644126,-1.204217 0,-0.637457 3.194333,-0.228185 6.146722,0.787546 1.962423,0.675154 2.084926,0.678554 1.820624,0.05 -0.155848,-0.370374 -0.373991,-1.094625 -0.484774,-1.60929 -0.190397,-0.884639 -0.123043,-0.923137 1.229652,-0.703652 0.787102,0.127692 1.978022,0.505066 2.6465,0.838543 1.586867,0.791646 1.874234,0.765248 1.874234,-0.172088 0,-1.091626 1.111232,-0.977333 1.543162,0.158689 0.35108,0.923437 0.38864,0.904938 2.5755,-1.264914 1.22864,-1.219117 2.29159,-1.997663 2.38137,-1.744181 0.0892,0.251683 0.001,1.346308 -0.19677,2.432434 -0.36665,2.017263 -0.15885,2.428735 0.70242,1.391005 0.26649,-0.321078 0.95502,-0.58386 1.5301,-0.58386 1.67471,0 2.52345,-0.846542 2.24491,-2.239247 -0.28425,-1.421203 -2.55235,-6.322169 -2.92746,-6.325668 -0.14505,0 -0.56075,-0.524465 -0.92378,-1.162421 -0.38161,-0.670654 -1.43995,-1.531496 -2.50902,-2.040861 -1.99815,-0.951935 -4.358259,-3.107088 -3.658149,-3.340472 0.236749,-0.07899 0.994009,0.284881 1.682809,0.808445 0.68878,0.523564 1.7848,1.226116 2.43557,1.561193 2.63512,1.356808 6.00118,6.198877 6.64534,9.559248 0.13663,0.712752 0.48903,1.388305 0.7831,1.501098 0.29406,0.112892 0.53465,0.451969 0.53465,0.753448 0,0.30158 0.21779,1.246115 0.48398,2.099057 0.37668,1.206918 0.67861,1.550694 1.36222,1.550694 0.52119,0 0.87825,0.237284 0.87825,0.583761 0,0.671754 1.38847,0.776547 2.86284,0.215985 0.74839,-0.284581 0.91668,-0.562162 0.74313,-1.225817 -0.26414,-1.010031 0.73621,-1.909169 2.12409,-1.909169 1.20216,0 1.48418,0.863741 0.75757,2.320341 -0.53938,1.081227 -0.53412,1.18202 0.0614,1.17672 1.44521,-0.013 2.5973,-0.965634 2.5973,-2.148253 0,-1.02863 0.14415,-1.168021 1.3244,-1.280613 1.06581,-0.101693 1.49304,0.08499 2.18813,0.956835 l 0.86373,1.083226 1.62166,-1.637988 c 1.80023,-1.818276 3.22762,-2.123655 4.01712,-0.859542 0.81112,1.298812 2.78452,0.926237 3.09156,-0.58376 0.19626,-0.965334 1.62554,-0.896239 3.05379,0.14759 0.63571,0.464668 2.0528,0.817744 4.0225,1.002332 l 3.04105,0.28488 -0.46895,-0.911938 c -0.25791,-0.501665 -1.14853,-1.731581 -1.97915,-2.733313 -1.72531,-2.080658 -1.9251,-3.040993 -0.6345,-3.049792 1.83488,-0.013 1.87976,-0.467568 0.34605,-3.508561 -1.21155,-2.402336 -1.87973,-3.211581 -3.84666,-4.658782 -2.50403,-1.842374 -3.12749,-3.113587 -1.52704,-3.113587 0.86603,0 0.89963,-0.102793 0.73135,-2.237948 -0.097,-1.230816 -0.56322,-3.201681 -1.03602,-4.379701 -1.30375,-3.248378 -1.49379,-4.261409 -0.84606,-4.509892 0.46749,-0.179388 0.43845,-0.543063 -0.18505,-2.317042 -0.83968,-2.389037 -0.91656,-3.167284 -0.3321,-3.362171 0.61366,-0.204486 -0.52849,-2.648019 -2.13854,-4.575088 -0.7604,-0.910137 -1.38256,-1.994363 -1.38256,-2.409335 0,-1.393705 -1.92411,-4.568868 -4.32231,-7.132544 -2.9489,-3.152364 -2.87876,-3.729955 0.41668,-3.431135 l 2.3726,0.215165 -4.30554,-4.305536 c -3.28792,-3.287916 -4.96953,-4.647773 -7.11488,-5.753568 -1.54513,-0.796415 -3.56862,-1.967416 -4.49665,-2.602212 -0.92802,-0.634787 -2.24158,-1.196889 -2.91904,-1.249105 -0.67743,-0.0522 -2.4577,-0.211386 -3.95614,-0.353706 -1.81851,-0.172708 -2.4656,-0.136421 -1.94602,0.109133 0.42811,0.202326 1.17248,0.380184 1.65411,0.395233 1.08164,0.0338 1.19037,1.194968 0.11191,1.194968 -0.4201,0 -1.68989,-0.333957 -2.82174,-0.742119 -1.13186,-0.408172 -2.75849,-0.851902 -3.61474,-0.986053 -3.838877,-0.601499 -7.200292,-1.554054 -7.200292,-2.040431 0,-0.281951 0.273044,-0.512645 0.606778,-0.512645 0.863239,0 0.285262,-0.753978 -0.936494,-1.221666 -1.378599,-0.527724 -12.872031,-1.491189 -17.865612,-1.497618 -4.046334,-0.005 -5.019747,-0.374815 -2.724425,-1.03447 1.148028,-0.329937 0.897691,-0.423871 -2.709389,-1.01662 -2.185872,-0.359206 -4.30816,-0.861592 -4.716203,-1.116414 -0.993803,-0.620638 -2.012625,-0.175178 -2.012625,0.88 0,0.526504 -0.711202,1.499428 -1.848719,2.529027 -1.605638,1.453311 -2.084247,1.675046 -3.639889,1.686345 -1.234182,0.009 -2.202399,-0.262792 -3.113639,-0.87394 -1.160885,-0.778577 -1.389476,-1.220807 -1.87077,-3.619373 -0.665363,-3.315884 -0.432327,-4.432338 1.116084,-5.347005 0.650997,-0.384554 1.183632,-1.019551 1.183632,-1.411104 0,-0.391553 0.254646,-1.202738 0.56587,-1.802627 0.520306,-1.002892 0.502934,-1.134803 -0.215776,-1.638208 -1.047872,-0.73396 -4.789401,-2.0305022 -5.85955,-2.0305022 -0.472152,0 -2.12521,-0.8757102 -3.673481,-1.9460172 C 45.159946,5.9191699 43.304607,5.3693674 42.976069,6.3549502 42.860881,6.7005266 42.244683,6.5315082 40.847644,5.77115 38.453685,4.4681889 36.846067,4.3509569 36.846067,5.4793599 c 0,0.4281308 -0.235572,0.7784169 -0.523493,0.7784169 -0.287923,0 -0.840493,-0.3502861 -1.227935,-0.7784169 -1.004138,-1.1095443 -4.684894,-1.0880857 -6.59423,0.038497 -0.761463,0.4492694 -1.384462,0.9683539 -1.384462,1.1535213 0,0.1851674 -1.700128,0.3867936 -3.778044,0.4480594 -3.670713,0.1082327 -3.784488,0.1390406 -4.005304,1.0844061 -0.156,0.6678844 -0.527381,1.0059213 -1.184328,1.0779764 -2.755107,0.3021694 -3.410134,0.5325337 -3.602161,1.2668439 -0.116173,0.44423 -0.531625,0.768818 -0.984064,0.768818 -1.646751,0 -2.047095,0.402332 -1.785882,1.794777 0.15654,0.834414 0.0176,1.887952 -0.380035,2.881744 -0.8977,2.243557 -0.807586,2.561485 0.922189,3.253598 2.541421,1.016881 3.478749,4.268319 1.95224,6.772028 -0.814456,1.335809 -2.190494,2.068749 -3.981666,2.120805 -1.2604158,0.0366 -2.660243,1.750241 -1.8425472,2.255596 0.3218887,0.198937 0.3869843,0.670515 0.194737,1.410554 -0.1588394,0.611438 -0.3141791,1.773769 -0.345197,2.582944 -0.042097,1.098285 -0.3863843,1.808976 -1.3581699,2.803549 -1.4270954,1.46054 -2.2847285,4.068542 -1.4986906,4.557389 0.512196,0.318518 -0.2843512,2.853265 -2.2378015,7.121064 -0.9982038,2.180841 -1.0122029,2.281534 -0.2171257,1.561983 0.8202156,-0.742279 0.8680425,-0.744259 1.3706891,-0.057 0.3537666,0.483767 0.4893376,1.694484 0.422622,3.774243 -0.072295,2.253546 0.037797,3.114087 0.4170123,3.259677 0.3786749,0.14529 0.5152558,1.201218 0.5152558,3.983628 l 0,3.786042 1.35777,-1.31601 1.3577399,-1.31601 0.9762853,0.917237 c 0.7870175,0.73935 0.9576465,1.236116 0.8801615,2.562325 -0.0528,0.904739 -0.227465,1.564993 -0.3880042,1.4671 -0.4167224,-0.254183 -0.3604961,0.804445 0.1081932,2.037161 0.220045,0.578761 0.510436,0.941936 0.645287,0.807145 0.293471,-0.29348 1.289845,0.468368 1.289845,0.986233 0,0.200486 -0.16967,0.259682 -0.377056,0.131491 -0.207397,-0.128191 0.119522,0.781047 0.726472,2.020362 0.606929,1.239315 1.19203,2.516128 1.300204,2.837206 0.109703,0.325578 0.323938,-0.06199 0.484458,-0.87574 0.158279,-0.802745 0.415392,-1.4595 0.571382,-1.4595 0.264792,0 0.849803,1.633688 1.393657,3.892034 0.128892,0.535164 0.438551,1.498398 0.688135,2.140654 l 0.453799,1.16762 0.205027,-1.16762 c 0.232534,-1.324209 1.277015,-1.596191 1.494831,-0.389273 0.167243,0.926736 1.959397,2.919101 2.625786,2.919101 0.258136,0 0.570163,-0.262783 0.693373,-0.583861 0.425857,-1.109724 1.022493,-0.573061 2.396742,2.155853 0.758815,1.506798 1.549228,2.634821 1.756456,2.506829 0.207228,-0.128091 1.219928,0.478068 2.250429,1.346908 1.0305,0.868941 2.16886,1.579893 2.529691,1.579893 0.36083,0 0.661386,0.218985 0.667898,0.486566 0.0065,0.269982 0.188373,0.183388 0.40854,-0.194686 0.664114,-1.140123 1.59727,-0.780447 3.908693,1.506897 2.025246,2.004063 2.369536,2.19445 4.097925,2.265745 1.037816,0.043 2.902547,0.371875 4.14385,0.73115 3.623292,1.048729 3.775758,0.992333 3.775758,-1.394804 0,-1.126424 -0.410285,-3.359471 -0.911738,-4.962362 -0.501453,-1.602891 -1.142641,-4.029025 -1.424869,-5.391432 -0.302135,-1.458601 -0.901044,-2.9381 -1.456537,-3.598255 -0.51888,-0.616657 -0.876552,-1.321809 -0.794833,-1.566993 0.224407,-0.673154 2.18198,1.306511 2.386603,2.413636 0.09631,0.521064 0.524573,2.085857 0.951678,3.477262 1.992705,6.491658 2.538237,8.927691 2.57384,11.493116 l 0.03806,2.743413 1.09428,-0.160289 c 0.861258,-0.126091 1.140287,0.016 1.310319,0.665855 0.295984,1.131922 1.29285,1.055328 1.29285,-0.09899 0,-1.103324 0.858625,-1.915069 1.830028,-1.730182 0.481795,0.09199 0.77354,0.617058 0.93648,1.686285 0.130164,0.854142 0.261518,1.334109 0.291898,1.066528 0.03211,-0.282781 0.608274,-0.486467 1.375802,-0.486467 0.834716,0 1.405904,-0.222385 1.552469,-0.604259 0.177596,-0.462868 0.711369,-0.576761 2.279575,-0.486567 1.89827,0.109193 2.056487,0.194187 2.168517,1.164121 0.112874,0.977333 0.367009,1.145822 2.15872,1.430501 0.183456,0.029 0.234519,-0.205085 0.113471,-0.520463 z M 64.993232,39.104046 C 64.861047,38.7596 64.318825,38.01525 63.78828,37.449929 c -0.530545,-0.565321 -1.417292,-1.728422 -1.970543,-2.584664 -1.984615,-3.07153 -3.191728,-4.39529 -4.245668,-4.655942 -1.266916,-0.313339 -2.874107,-1.956996 -3.580058,-3.6613 -0.277532,-0.670044 -0.753176,-1.218257 -1.05696,-1.218257 -0.303797,0 -1.065175,-0.379144 -1.691969,-0.842553 -0.626781,-0.463398 -1.451606,-0.87518 -1.832953,-0.915057 -1.771223,-0.185228 -3.880267,-0.742709 -4.613286,-1.219417 -0.912086,-0.593159 -4.683648,-0.454459 -5.941075,0.218495 -0.495773,0.265332 -2.488576,0.323678 -5.625419,0.164689 -4.54592,-0.230394 -4.87299,-0.198777 -5.096259,0.492716 -0.270107,0.836483 -1.910904,1.711923 -3.208603,1.711923 -0.477182,0 -1.099045,0.251213 -1.381923,0.558262 -2.877446,3.123377 -3.73103,4.10251 -4.35709,4.998009 -0.448602,0.641667 -1.245599,1.139833 -2.071435,1.294762 -1.719125,0.322508 -2.028644,1.148812 -1.772312,4.731377 0.167179,2.336591 0.101763,2.818138 -0.382885,2.818138 -0.387433,0 -0.73923,-0.689023 -1.04329,-2.043321 -0.252313,-1.123823 -0.553054,-2.408135 -0.668326,-2.854025 -0.164339,-0.635697 0.658736,-1.694844 3.812717,-4.906285 2.212265,-2.252566 4.416369,-4.302107 4.898012,-4.554539 0.767319,-0.402163 0.881427,-0.782417 0.921947,-3.072401 0,0 0.167705,-2.48964 0.36108,-2.665918 0.135565,-0.123561 -0.05013,1.408094 0.07531,2.095807 0.403969,2.215059 0.672745,2.429774 1.740136,1.31826 0.382954,-0.398783 0.92098,-0.794776 1.195617,-0.87999 0.353308,-0.109622 0.211022,-0.545733 -0.486506,-1.491178 -0.542222,-0.73494 -0.985853,-1.652507 -0.985853,-2.039041 0,-0.986903 -0.642018,-0.87716 -0.912181,0.155919 -0.1235,0.472288 -0.342794,0.74047 -0.487323,0.59595 -0.388841,-0.388844 0.935082,-2.233068 1.603075,-2.233068 0.352726,0 0.574846,0.373605 0.574846,0.966864 0,0.531784 0.504113,1.531086 1.120269,2.220679 l 1.120269,1.253794 3.647492,-0.110142 c 2.006116,-0.0606 3.81387,-0.271742 4.017219,-0.469258 0.203363,-0.197507 1.303503,-0.359106 2.444746,-0.359106 1.507765,0 2.137775,-0.163559 2.304591,-0.598299 0.37269,-0.971204 2.754956,-1.76286 4.476447,-1.487579 1.257913,0.201147 1.66126,0.496647 2.253462,1.650958 0.561063,1.093615 1.399227,1.75579 3.772976,2.980696 2.037105,1.051199 3.352618,2.022002 3.956832,2.920021 0.497838,0.7399 1.375386,1.589632 1.950122,1.888291 1.528476,0.794286 2.999421,2.095687 4.985837,4.411169 1.771542,2.06502 4.427035,6.411323 4.427035,7.245846 0,0.671754 -0.767418,0.541423 -1.043163,-0.177168 z M 11.269177,94.728991 c 1.97273,-0.059 4.543359,0.08499 5.712511,0.319778 1.558127,0.313079 2.164349,0.311279 2.270366,-0.01 0.07954,-0.238684 0.827651,-0.43387 1.662463,-0.43387 1.427419,0 1.575703,-0.114293 2.489449,-1.91747 0.534395,-1.054528 1.197278,-2.587023 1.47305,-3.405467 0.472915,-1.403505 0.429721,-1.62099 -0.75952,-3.823439 -0.693527,-1.284313 -1.265879,-2.028662 -1.271892,-1.654088 -0.01711,1.064728 -1.038548,0.805545 -2.684125,-0.681053 -1.67184,-1.510397 -1.997279,-1.637288 -1.997279,-0.778447 0,0.321078 -0.258673,0.58376 -0.574832,0.58376 -0.679555,0 -1.601654,-1.32021 -1.970829,-2.821707 -0.278122,-1.131223 -0.957176,-1.4764 -0.957176,-0.486467 0,0.321078 -0.262713,0.58376 -0.583811,0.58376 -0.3211,0 -0.583802,-0.266082 -0.583802,-0.59126 0,-0.88044 -2.065763,-6.025189 -2.41924,-6.025189 -0.167868,0 -0.3052,1.291712 -0.3052,2.870405 0,4.24801 -0.272411,5.215344 -1.5474767,5.495425 -1.115286,0.244983 -1.9553703,1.540595 -1.9553703,3.015794 0,0.460069 -0.4520899,1.488798 -1.0046333,2.286144 -0.5525533,0.797346 -1.4911111,2.645719 -2.0857016,4.10752 -1.0133428,2.49143 -1.0357514,2.665718 -0.3575863,2.78231 0.3979236,0.06799 0.723482,0.317278 0.723482,0.553162 0,0.235784 0.2923306,0.751749 0.6495969,1.146522 0.6293883,0.695552 0.678245,0.690953 1.5701959,-0.14699 0.7916075,-0.743649 1.4231656,-0.87994 4.5073604,-0.972334 z m 16.235942,-6.954925 c 0,-0.333678 -0.177996,-0.496767 -0.39556,-0.362276 -0.217548,0.134391 -0.294778,0.407472 -0.171625,0.606759 0.333637,0.539863 0.567185,0.43917 0.567185,-0.244483 z M 12.847582,24.457245 c 0.547824,-0.647445 0.578542,-1.545714 0.158121,-2.399266 -0.711054,-1.443571 -1.766534,-1.990184 -3.2375056,-1.620989 -0.6496472,0.163049 -1.2217492,0.953055 -1.2217492,0.953055 0,0 0.75336,0.0457 1.4694528,0.188907 1.073188,0.214635 1.180391,0.365685 1.070329,1.508157 -0.107663,1.117504 -0.549294,1.536705 -1.3872379,1.644058 -1.1454344,0.14673 -1.1231259,0.0294 -1.1231259,0.0294 0,0 0.9659859,0.907728 2.1547768,0.839632 0.936848,-0.0537 1.50899,-0.424401 2.116939,-1.142932 z m -2.593158,-0.918177 c 0.275353,-0.717541 -0.665905,-1.46544 -1.1876604,-0.943686 -0.4248118,0.424811 -0.034898,1.566774 0.5349246,1.566774 0.227505,0 0.5212348,-0.280391 0.6527358,-0.623088 z m 52.186147,-0.453619 c 1.311511,-0.857381 2.242533,-3.056021 1.809125,-4.272358 -0.504541,-1.415954 -2.259931,-2.590694 -3.871235,-2.590694 -1.021372,0 -1.68665,0.314929 -2.604831,1.233116 -1.53845,1.538435 -1.633468,3.529649 -0.240059,5.029967 1.619436,1.743681 2.915139,1.9021 4.907,0.599969 z M 58.78795,20.321288 c -0.134069,-0.670335 -0.397,-1.218787 -0.584307,-1.218787 -0.187307,0 -0.34055,-0.358636 -0.34055,-0.796976 0,-0.73052 0.202837,-0.787316 2.432527,-0.681113 2.391535,0.113922 2.43423,0.13555 2.533916,1.283492 0.142577,1.641718 -0.333,2.167452 -2.185415,2.415905 -1.557193,0.208866 -1.620751,0.174528 -1.856171,-1.002521 z m 2.537395,-0.407063 c 0.661483,-0.418841 0.28727,-1.590131 -0.508033,-1.590131 -0.731759,0 -1.139219,0.654005 -0.860303,1.380856 0.247998,0.646286 0.594476,0.699282 1.368336,0.209275 z M 8.187662,21.740331 c 0,0 -0.512976,0.501916 -0.5012668,1.481249 0.008,0.660265 0.5388542,1.191108 0.5388542,1.191108 0,0 -0.165679,-0.837522 -0.1592694,-1.248114 0.01,-0.641547 0.121682,-1.424243 0.121682,-1.424243 z m 53.123899,-6.280322 c 0.973717,0.367535 1.153322,0.272342 2.245013,-1.189939 0.652644,-0.87419 1.373599,-2.114855 1.602132,-2.757041 0.228535,-0.642187 0.544869,-1.5178972 0.702987,-1.9460279 1.352169,-3.6613902 1.904283,-5.5571409 1.558538,-5.3515049 -1.095902,0.6518155 -5.271447,3.5123003 -6.791375,4.6524826 -0.940553,0.7055518 -2.049781,1.2828332 -2.464971,1.2828332 -0.430913,0 -0.685269,0.208785 -0.592703,0.486506 0.08919,0.267572 0.40789,0.486497 0.70821,0.486497 0.815778,0 0.883907,1.159741 0.158007,2.689457 -0.806081,1.698714 -0.509043,2.306502 0.829244,1.696744 0.6989,-0.318438 1.295122,-0.333027 2.044918,-0.05 z M 10.963908,12.648331 c 0,-0.944675 -0.130892,-1.717933 -0.290871,-1.718353 C 10.097175,10.928978 5.3813481,8.5593298 4.3687153,7.7627842 3.7993131,7.3148947 3.2562891,7.0255745 3.1619954,7.119858 c -0.3898842,0.3898934 1.4365347,3.337613 3.0042807,4.84868 1.5718657,1.515046 4.1613639,2.960648 4.6030339,2.569654 0.107034,-0.09479 0.194598,-0.945195 0.194598,-1.889861 z"
               id="path3014-1-4"
               style="fill:#1b1b1b;fill-opacity:1" />
        </g>
    </g>
</svg>