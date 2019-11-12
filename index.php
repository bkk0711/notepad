<?php 
// hàm random chuỗi
function rand_string($length, $max) {
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";	
    // lấy chiều dài của chuỗi
	$size = strlen($chars);
	// chạy vòng lặp lấy ra ký tự ngẫu nhiên, số ký tự tối đa bằng $max - 1
	for( $i = 0; $i < $max; $i++ ) 
	{
		// cái này là gán giá trị
		$str .= $chars[ rand( 0, $size - 1 ) ];
	}
	// trả về chuỗi vừa random
	return $str; 
}
// lấy ra chuỗi ngẫu nhiên và gán
$access_url = rand_string(5,rand(5,9));
// chuyễn trang sau khi đã có được ID note 
header("Location: /$access_url");
	// dừng các mã chạy phía dưới;
    exit; 
    