<?php

namespace RefChecker\Http\Controllers;

use RefChecker\Http\Controllers\Controllers;
use Illuminate\Http\Request;
use RefChecker\Ticket;
use DB;
use Storage;
use Auth;
use File;

/**
* 
*/
class ResultController extends Controller
{
	
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function viewResults() {
    	return view('result');
    }

    public function getResult() {
        // $result = DB::table('user_file_result')->where('user_id', Auth::user()->id)->pluck('result')->first();

        // Test JSON
        $json = '{
  "original": [
    "[1]. Bộ KHCN, TCVN 7957:2008. “Thoát nước – Mạng lưới và công trình bên ngoài – Tiêu chuẩn thiết kế”. NXBXD (2008). ",
    "[2]. Chow, Ven T, David R Maidment, and Larry W Mays. Applied Hydrology. McGraw-Hill (1998). ",
    "[3]. Công ty cổ phần Tư vấn xây dựng Thủy Lợi II. Quy hoạch tổng thể tủy lợi và cấp thoát nước tỉnh Bình Dương - giai đoạn 2005-2010 và định hướng đến năm 2020, Sở NN\u0026PTNT Bình Dương. Sở NNPTNN Bình Dương (2005). ",
    "[4]. Harbor, J., A practical method for estimating the impact of land use change on surface runoff, groundwater recharge and wetland hydrology, Journal of American Planning Association, 60: 91– 104 (1994). ",
    "[5]. Li, Y., and C. Wang, Impacts of urbanization on surface runoff of the Dardenne Creek watershed, St. Charles County, Missouri, Physical Geography, 30(6): 556–573 (2009). ",
    "[6]. Lương Văn Việt, Ảnh hưởng của sự phát triển đô thị, biến đổi khí hậu toàn cầu đến gia tăng cường độ mưa và việc xây dựng biểu đồ mưa thiết kế cho Tp.HCM, Tạp chí Khí tượng Thủy văn, số 584, t.24-30 (2009). ",
    "[7]. Nguyễn Thanh Sơn, Áp dụg mô hình 1DKWM – FEM \u0026 SCS đánh giá tác động của quá trình đô thị hóa đến dòngScience \u0026 Technology Development, Vol 19, No.M1-2016 Trang 78 chảy lũ trên một số sông ngòi Miền Trung, Tạp chí khoa học Đại học Quốc gia Hà Nội, 2B PT, tr.149-157 (2006) ",
    "[8]. Viện Quy hoạch Xây dựng Miền Nam. Quy hoạch cao độ nền và thoát mặt đô thị Bình Dương đến năm 2030 tầm nhìn đến năm 2050. Đồ án quy hoạch đô thị - Sở XD Bình Dương (2015). ",
    "[9]. Zheng, J., W. Fang, P. Shi, and L. Zhuo, Modeling the impacts of land use change on hydrological Processes in fast urbanizing region - A case study of the Buji watershed in Shenzhen City, China, Journal of Natural Resources, 24(9), 1560–1572 (2009). ",
    "[10].Weng, Q., Modeling urban growth effects on surface runoff with the integration of remote sensing and GIS, Environmental Management, 28(6): 737-748 (2001)."
  ],
  "APA": [
    "[1]. Bộ KHCN, TCVN 7957:2008. “Thoát nước – Mạng lưới và công trình bên ngoài – Tiêu chuẩn thiết kế”. NXBXD (2008). ",
    "[2]. Chow, Ven Te, David R Maidment, and Larry W Mays. Applied Hydrology. McGraw-Hill (1998). ",
    "[3]. Công ty cổ phần Tư vấn xây dựng Thủy Lợi II. Quy hoạch tổng thể thủy lợi và cấp thoát nước tỉnh Bình Dương - giai đoạn 2005-2010 và định hướng đến năm 2020, Sở NN\u0026PTNT Bình Dương. Sở NNPTNN Bình Dương (2005). ",
    "[4]. Harbor, J., A practical method for estimating the impact of land use change on surface runoff, groundwater recharge and wetland hydrology, Journal of American Planning Association, 60: 91– 104 (1994). ",
    "[5]. Li, Y., and C. Wang, Impacts of urbanization on surface runoff of the Dardenne Creek watershed, St. Charles County, Missouri, Physical Geography, 30(6): 556–573 (2009). ",
    "[6]. Lương Văn Việt, Ảnh hưởng của sự phát triển đô thị, biến đổi khí hậu toàn cầu đến gia tăng cường độ mưa và việc xây dựng biểu đồ mưa thiết kế cho Tp.HCM, Tạp chí Khí tượng Thủy văn, số 584, t.24-30 (2009). ",
    "[7]. Nguyễn Thanh Sơn, Áp dụng mô hình 1DKWM – FEM \u0026 SCS đánh giá tác động của quá trình đô thị hóa đến dòngScience \u0026 Technology Development, Vol 19, No.M1-2016 Trang 78 chảy lũ trên một số sông ngòi Miền Trung, Tạp chí khoa học Đại học Quốc gia Hà Nội, 2B PT, tr.149-157 (2006) ",
    "[8]. Viện Quy hoạch Xây dựng Miền Nam. Quy hoạch cao độ nền và thoát mặt đô thị Bình Dương đến năm 2030 tầm nhìn đến năm 2050. Đồ án quy hoạch đô thị - Sở XD Bình Dương (2015). ",
    "[9]. Zheng, J., W. Fang, P. Shi, and L. Zhuo, Modeling the impacts of land use change on hydrological Processes in fast urbanizing region - A case study of the Buji watershed in Shenzhen City, China, Journal of Natural Resources, 24(9), 1560–1572 (2009). ",
    "[10].Weng, Q., Modeling urban growth effects on surface runoff with the integration of remote sensing and GIS, Environmental Management, 28(6): 737-748 (2001)."
  ],
  "Havard": [
    "[1]. Bộ KHCN, TCVN 7957:2008. “Thoát nước – Mạng lưới và công trình bên ngoài – Tiêu chuẩn thiết kế”. NXBXD (2008). ",
    "[2]. Chow, Ven Te, David R Maidment, and Larry W Mays. Applied Hydrology. McGraw-Hill (1998). ",
    "[3]. Công ty cổ phần Tư vấn xây dựng Thủy Lợi II. Quy hoạch tổng thể thủy lợi và cấp thoát nước tỉnh Bình Dương - giai đoạn 2005-2010 và định hướng đến năm 2020, Sở NN\u0026PTNT Bình Dương. Sở NNPTNN Bình Dương (2005). ",
    "[4]. Harbor, J., A practical method for estimating the impact of land use change on surface runoff, groundwater recharge and wetland hydrology, Journal of American Planning Association, 60: 91– 104 (1994). ",
    "[5]. Li, Y., and C. Wang, Impacts of urbanization on surface runoff of the Dardenne Creek watershed, St. Charles County, Missouri, Physical Geography, 30(6): 556–573 (2009). ",
    "[6]. Lương Văn Việt, Ảnh hưởng của sự phát triển đô thị, biến đổi khí hậu toàn cầu đến gia tăng cường độ mưa và việc xây dựng biểu đồ mưa thiết kế cho Tp.HCM, Tạp chí Khí tượng Thủy văn, số 584, t.24-30 (2009). ",
    "[7]. Nguyễn Thanh Sơn, Áp dụng mô hình 1DKWM – FEM \u0026 SCS đánh giá tác động của quá trình đô thị hóa đến dòngScience \u0026 Technology Development, Vol 19, No.M1-2016 Trang 78 chảy lũ trên một số sông ngòi Miền Trung, Tạp chí khoa học Đại học Quốc gia Hà Nội, 2B PT, tr.149-157 (2006) ",
    "[8]. Viện Quy hoạch Xây dựng Miền Nam. Quy hoạch cao độ nền và thoát mặt đô thị Bình Dương đến năm 2030 tầm nhìn đến năm 2050. Đồ án quy hoạch đô thị - Sở XD Bình Dương (2015). ",
    "[9]. Zheng, J., W. Fang, P. Shi, and L. Zhuo, Modeling the impacts of land use change on hydrological Processes in fast urbanizing region - A case study of the Buji watershed in Shenzhen City, China, Journal of Natural Resources, 24(9), 1560–1572 (2009). ",
    "[10].Weng, Q., Modeling urban growth effects on surface runoff with the integration of remote sensing and GIS, Environmental Management, 28(6): 737-748 (2001)."
  ]
}';

    	// Result
        echo $json;
    }
}