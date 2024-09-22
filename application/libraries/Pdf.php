<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * CodeIgniter PDF Library
 *
 * Generate PDF's in your CodeIgniter applications.
 *
 * @package         CodeIgniter
 * @subpackage      Libraries
 * @category        Libraries
 * @author          Chris Harvey
 * @license         MIT License
 * @link            https://github.com/chrisnharvey/CodeIgniter-  PDF-Generator-Library
*/
error_reporting(1);
require_once APPPATH.'third_party/dompdf/autoload.inc.php';

use Dompdf\Dompdf;
class Pdf extends DOMPDF
{
/**
 * Get an instance of CodeIgniter
 *
 * @access  protected
 * @return  void
 */
protected function ci()
{
    return get_instance();
}

/**
 * Load a CodeIgniter view into domPDF
 *
 * @access  public
 * @param   string  $view The view to load
 * @param   array   $data The view data
 * @return  void
 */
public function load_view($view='', $data = array(),$file_name='')
{

    $dompdf = new Dompdf();
    $html = $this->ci()->load->view($view, $data, TRUE);
    $dompdf->loadHtml($html);
    $dompdf->set_option('isRemoteEnabled',TRUE);


    // (Optional) Setup the paper size and orientation
    $dompdf->setPaper('A4', 'portrait');
    // Render the HTML as PDF
    $dompdf->render();
    // Output the generated PDF to Browser

    $output = $dompdf->output();
   // $fileName="a.pdf";
    file_put_contents('uploads/invoice/'.$file_name, $output);

}

public function download_invoice($view='', $data = array(),$file_name='')
{
    //echo $file_name;
 
    $dompdf = new Dompdf();
    $html = $this->ci()->load->view($view, $data, TRUE);
    $dompdf->loadHtml($html);
    $dompdf->set_option('isRemoteEnabled',TRUE);


    // (Optional) Setup the paper size and orientation
    $dompdf->setPaper('A4', 'portrait');
    // Render the HTML as PDF
    $dompdf->render();
    // Output the generated PDF to Browser
    $dompdf->stream($file_name);
}

public function open_invoice($view='', $data = array(),$file_name='')
{
    //echo $file_name;
 
    $dompdf = new Dompdf();
    $html = $this->ci()->load->view($view, $data, TRUE);
    $dompdf->loadHtml($html);
    $dompdf->set_option('isRemoteEnabled',TRUE);


    // (Optional) Setup the paper size and orientation
    $dompdf->setPaper('A4', 'portrait');
    // Render the HTML as PDF
    $dompdf->render();
    // Output the generated PDF to Browser
//    $dompdf->stream($file_name);


$dompdf->stream($file_name, array("Attachment" => false));

exit(0);

}


}