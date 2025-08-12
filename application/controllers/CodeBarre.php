<?php
class CodeBarre extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('Zend');
        $this->load->library('barcode_lib');
        $this->load->model('CodeModel', 'code');

        require "vendor/autoload.php";
    }
    public function index()
    {
        $this->jail();

        $page = (isset($_GET['page'])) ? $_GET['page'] : 1;


        $data['title'] = 'CodeBarre';
        $data['css'] = 'stock.css';
        $js['js'] = 'code.js';


        $all_produit = $this->code->getAllProduit($page);



        $lien = $this->pagination('CodeBarre', count($this->code->getAllProduit()));


        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', ['uniter' => true]);
        $this->load->view('templates/tete');
        $this->load->view('code', [
            'data' => $all_produit,
            'lien' => $lien
        ]);
        $this->load->view('templates/footer', $js);
    }
    public function codeBarreNum()
    {
        $this->jail();

        $page = (isset($_GET['page'])) ? $_GET['page'] : 1;


        $data['title'] = 'CodeBarre-Num';
        $data['css'] = 'stock.css';
        $js['js'] = 'code.js';


        $all_produit = $this->code->getAllProduit_num($page);

        // echo '<pre>' ; 
        // var_dump( $all_produit ) ; 
        // echo '</pre>' ; die  ;



        $lien = $this->pagination('CodeBarre', count($this->code->getAllProduit()));


        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', ['uniter' => true]);
        $this->load->view('templates/tete');
        $this->load->view('codenum', [
            'data' => $all_produit,
            'lien' => $lien
        ]);
        $this->load->view('templates/footer', $js);
    }
    public function creatCode_num($reference = '123456', $produit = 'ref')
    {
        try {
            // Créer une instance du générateur de code-barres
            $bar = new Picqer\Barcode\BarcodeGeneratorPNG();
            $barcodeImage = $bar->getBarcode($produit, $bar::TYPE_CODE_128);

            // Créer une ressource d'image à partir du code-barres PNG
            $barcodeResource = imagecreatefromstring($barcodeImage);
            if (!$barcodeResource) {
                throw new Exception('Impossible de créer une ressource d\'image à partir du code-barres.');
            }
            $barcodeWidth = imagesx($barcodeResource);
            $barcodeHeight = imagesy($barcodeResource);

            // Créer une image GD pour la sortie finale
            $imageWidth = $barcodeWidth;
            $imageHeight = $barcodeHeight + 55;
            $image = imagecreatetruecolor($imageWidth, $imageHeight);

            // Créer une couleur de fond et dessiner le fond de l'image
            $backgroundColor = imagecolorallocate($image, 255, 255, 255);
            imagefill($image, 0, 0, $backgroundColor);

            // Définir les couleurs pour le texte
            $textColor = imagecolorallocate($image, 0, 0, 0);

            // Définir la police et la taille du texte
            $fontPath = realpath(FCPATH . 'public/fonts/Poppins/Poppins-Regular.ttf');
            if (!$fontPath) {
                throw new Exception('La police spécifiée est introuvable.');
            }
            $fontSize = 12;

            // Ajouter le texte du produit en haut à gauche de l'image
            imagettftext($image, $fontSize, 0, 2, 23, $textColor, $fontPath, $produit);

            // Copier le code-barres sur l'image avec un espace blanc en haut
            imagecopy($image, $barcodeResource, 0, 30, 0, 0, $barcodeWidth, $barcodeHeight);

            // Ajouter le texte de la référence aligné à gauche
            imagettftext($image, $fontSize, 0, 2, $barcodeHeight + 45, $textColor, $fontPath, $reference);

            // Spécifier le type de contenu
            header('Content-Type: image/png');

            // Envoyer l'image au navigateur
            imagepng($image);

            // Libérer la mémoire
            imagedestroy($image);
            imagedestroy($barcodeResource);
        } catch (Exception $e) {
            // Gestion des exceptions
            header('Content-Type: text/plain');
            echo 'Erreur : ' . $e->getMessage();
        }
    }
    public function imprimNum($ref = '')
    {
        $this->jail();
        $data['title'] = 'Impression';
        $data['css'] = 'stock.css';
        $js['js'] = 'code.js';

        $limit = 20;

        $page = (isset($_GET['page'])  ? $_GET['page'] : 0);

        $all = $this->code->getAllProduit_imprim_num($page,$ref  , $limit);

        $lien = $this->pagination('codeBarre-num/'. $ref , count($this->code->getAllProduit_imprim_num('',$ref  , $limit)), $limit);

        $this->load->view('templates/header', $data);
        $this->load->view('impressionnum', [
            'code' => $all,
            'lien' => $lien
        ]);

        $this->load->view('templates/footer', $js);
    }

    public function creatCode($reference = '123456', $produit = 'ref')
    {
        try {
            // Créer une instance du générateur de code-barres
            $bar = new Picqer\Barcode\BarcodeGeneratorPNG();
            $barcodeImage = $bar->getBarcode($reference, $bar::TYPE_CODE_128);

            // Créer une ressource d'image à partir du code-barres PNG
            $barcodeResource = imagecreatefromstring($barcodeImage);
            if (!$barcodeResource) {
                throw new Exception('Impossible de créer une ressource d\'image à partir du code-barres.');
            }
            $barcodeWidth = imagesx($barcodeResource);
            $barcodeHeight = imagesy($barcodeResource);

            // Créer une image GD pour la sortie finale
            $imageWidth = $barcodeWidth;
            $imageHeight = $barcodeHeight + 30;
            $image = imagecreatetruecolor($imageWidth, $imageHeight);

            // Créer une couleur de fond et dessiner le fond de l'image
            $backgroundColor = imagecolorallocate($image, 255, 255, 255);
            imagefill($image, 0, 0, $backgroundColor);

            // Définir les couleurs pour le texte
            $textColor = imagecolorallocate($image, 0, 0, 0);

            // Définir la police et la taille du texte
            $fontPath = realpath(FCPATH . 'public/fonts/Poppins/Poppins-Regular.ttf');
            if (!$fontPath) {
                throw new Exception('La police spécifiée est introuvable.');
            }
            $fontSize = 12;

            // Ajouter le texte du produit en haut à gauche de l'image
            // imagettftext($image, $fontSize, 0, 2, 23, $textColor, $fontPath, $produit);

            // Copier le code-barres sur l'image avec un espace blanc en haut
            imagecopy($image, $barcodeResource, 0, 10, 0, 0, $barcodeWidth, $barcodeHeight);

            // Ajouter le texte de la référence aligné à gauche
            imagettftext($image, $fontSize, 0, 2, $barcodeHeight + 25, $textColor, $fontPath, $reference);

            // Spécifier le type de contenu
            header('Content-Type: image/png');

            // Envoyer l'image au navigateur
            imagepng($image);

            // Libérer la mémoire
            imagedestroy($image);
            imagedestroy($barcodeResource);
        } catch (Exception $e) {
            // Gestion des exceptions
            header('Content-Type: text/plain');
            echo 'Erreur : ' . $e->getMessage();
        }
    }

    public function search()
    {
        $keyword = strip_tags(trim($_GET['recherche']));

        $page = isset($_GET['page']) ? $_GET['page'] : 0;

        $datas = $this->code->getAllProduit_search($keyword, $page);

        $lien = $this->pagination_search('CodeBarre/search', count($this->code->getAllProduit_search($keyword)));
        $_POST['keyword'] = $keyword;

        $data['title'] = 'CodeBarre';
        $data['css'] = 'stock.css';
        $js['js'] = 'code.js';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', ['uniter' => true]);
        $this->load->view('templates/tete');
        $this->load->view('code', [
            'data' => $datas,
            'lien' => $lien
        ]);
        $this->load->view('templates/footer', $js);

        $_SERVER['PHP_SELF'] == 'produit';
    }

    public function impression()
    {
        $this->jail();
        $data['title'] = 'Impression';
        $data['css'] = 'stock.css';
        $js['js'] = 'code.js';

        $limit = 20;

        $page = (isset($_GET['page'])  ? $_GET['page'] : 0);

        $all = $this->code->getAllProduit_imprim($page, $limit);

        $lien = $this->pagination('CodeBarre/impression', count($this->code->getAllProduit_imprim('', $limit)), $limit);

        $this->load->view('templates/header', $data);
        $this->load->view('impression', [
            'code' => $all,
            'lien' => $lien
        ]);

        $this->load->view('templates/footer', $js);
    }
}
