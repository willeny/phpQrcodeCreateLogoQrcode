<?php
	/**
	 * 功能：生成带logo二维码
	 * @param string $qr_data     手机扫描后"要跳转的网址"或"要显示的内容"
	 * @param string $qr_level    默认纠错比例 分为L、M、Q、H四个等级，H代表最高纠错能力
	 * @param string $qr_size     二维码图大小，1－10可选，数字越大图片尺寸越大
	 * @param string $save_path   图片存储路径
     * @param  [type] $savefilename [图片存储文件名称]
     * @param  [type] $logo    [logo]
	 */
	function createLogoQRcode($qr_data,$qr_level='L',$qr_size='10',$save_path='Public/',$savefilename='qrcode.png',$logo='logo.png'){
		 //导入二维码核心程序
	    include 'phpqrcode.php'; 
	    $value = $qr_data;                  //二维码内容    
	    $errorCorrectionLevel = $qr_level;    //容错级别    
	    $matrixPointSize = $qr_size;           //生成图片大小  
	    //如果目录不存在，则赋予权限创建相对应的目录
        if(!file_exists($save_path)){
            mkdir($save_path,0755,true);
        }
        if(!file_exists($logo)){
        	return 'logo图片不存在！';
        	exit;
        }
	    //生成二维码图片的路径及名称  
	    $filename = $save_path.microtime().'.png';  
	    //生成二维码图片
	    QRcode::png($value,$filename , $errorCorrectionLevel, $matrixPointSize, 2);    
	       
	  	$qrcode = $filename;  //已经生成的原始二维码图
	    if (file_exists($qrcode)) {   
	    	//将生成的二维码放在背景图中 
	    	//imagecreatefromstring:创建一个图像资源从字符串中的图像流
	     	$QR = imagecreatefromstring(file_get_contents($qrcode));//目标图象连接资源。
            $logo = imagecreatefromstring(file_get_contents($logo));//源图象连接资源。
            $QR_width = imagesx($QR); //二维码图片宽度
            $QR_height = imagesy($QR);//二维码图片高度
            $logo_width = imagesx($logo);//logo图片宽度
            $logo_height = imagesy($logo);//logo图片高度
            $logo_qr_width = $QR_width / 5;//组合之后二维码的宽度
            $scale = $logo_width / $logo_qr_width;//qrcode的宽度缩放比(本身宽度/组合后的宽度)
            $logo_qr_height = $logo_height / $scale; //组合之后qrcode的高度
            $from_width = ($QR_width - $logo_qr_width) / 2;
            imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height); 
            /**     imagecopyresampled ( resource $dst_image , resource $src_image , int $dst_x , int $dst_y , int $src_x , int $src_y , int $dst_w , int $dst_h , int $src_w , int $src_h )
	         *      参数详情：
	         *      $dst_image:目标图象连接资源。
	         *      $src_image:源图象连接资源。
	         *      $dst_x:目标 X 坐标点。
	         *      $dst_y:目标 Y 坐标点。
	         *      $src_x:源的 X 坐标点。
	         *      $src_y:源的 Y 坐标点。
	         *      $dst_w:目标宽度。
	         *      $dst_h:目标高度。
	         *      $src_w:源图象的宽度。
	         *      $src_h:源图象的高度。
	         * */
	    }     
	    
	    //输出并保存图片begin 
	    imagepng($QR, $save_path.$savefilename); 
	    //输出并保存图片end
	    //输出图像到浏览器，不保存图片到本地begin
	    // header('Content-Type: image/png');
	    // imagepng($QR);
	    //输出图像到浏览器，不保存图片到本地end
	    imagedestroy($QR);  
	    //删除第一次生成二维码的文件
	    unlink($filename); 
	    // 返回生成的图片路径名称
	    return $save_path.$savefilename;
	}

	// 调用方法
	echo createLogoQRcode($qr_data='http://www.baidu.com',$qr_level='L',$qr_size='10',$save_path='Public/',$savefilename='haha.png',$logo='logo.png');