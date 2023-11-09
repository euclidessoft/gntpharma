<?php

namespace App\Entity;

use App\Repository\CvRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass=CvRepository::class)
 */
class Cv
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    private $file;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }


    public function getFile()
    {
        return $this->file;
    }

    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    public function upload($candidature) // id = id de la session en cour
    {
        if (null === $this->file) { return; } // On récupère le nom original du fichier de l'internaute
        $name = $this->file->getClientOriginalName(); // On déplace le fichier envoyé dans le répertoire de notre choix
        $this->file->move($this->getUploadRootDir(), $name); // On sauvegarde le n
        //move_uploaded_file($this->file->getPathname(),$setfile);
        $this->url = $name;

//        // Si jamais il n'y a pas de fichier (champ facultatif), on ne fait rien
//        if (null === $this->file)
//        { return; }
//        // On récupère le nom original du fichier de l'internaute
//        $name = $this->file->getClientOriginalName();
//        $extensions_valides = array( 'docx' ,'doc' , 'pdf'  );
//        //1. strrchr renvoie l'extension avec le point (« . »).
//        //2. substr(chaine,1) ignore le premier caractère de chaine.
//        //3. strtolower met l'extension en minuscules.
//        $extension_upload = strtolower(  substr(  strrchr($name, '.')  ,1)  );
//        if ( !in_array($extension_upload,$extensions_valides) ) {
//            //$errors[]="extension not allowed, please choose a JPEG or PNG file.";
//            return false;
//        }
//        else
//        {
//            $time = md5(uniqid(rand(), true));
//            if(!is_dir($this->getUploadRootDir($id)))mkdir($this->getUploadRootDir($id), 0777 );
//            $setfile = $this->getUploadRootDir($id).'/'.$candidature->getPrenom().$candidature->getNom().$time.'.'.$extension_upload ;;// creation de l'image de destination
//            move_uploaded_file($this->file->getPathname(),$setfile);// On sauvegarde le nom de fichier dans notre attribut $url
//            $this->url = $id.'/'.$user->getPrenom().$user->getNom().$time.'.'.$extension_upload ;
//            return true;
//        }
    }

    public function getUploadDir()
    {
        // On retourne le chemin relatif vers l'image pour un navigateur (relatif au répertoire /web donc)
        return 'Documents/CV';
    }

    protected function getUploadRootDir()
    {
        // On retourne le chemin relatif vers l'image pour notre code PHP
        return __DIR__.'../../public/'.$this->getUploadDir();
    }

}
