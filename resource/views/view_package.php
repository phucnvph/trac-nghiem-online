
<?php

/**
 * HỆ THỐNG TRẮC NGHIỆM ONLINE
 * View Package
 * @author: Package System
 */

class View_Package
{
    public function show_head_left($info)
    {
        require_once 'config/config.php';
        include 'res/templates/student/head_left.php';
    }
    
    public function show_packages($packages, $remaining_tests)
    {
        include 'res/templates/package/packages.php';
    }
    
    public function show_order_history($orders, $student_packages, $remaining_tests)
    {
        include 'res/templates/package/order_history.php';
    }
    
    public function show_foot()
    {
        require_once 'config/config.php';
        include 'res/templates/shared/foot.php';
    }
}
