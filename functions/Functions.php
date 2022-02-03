<?php
    class Functions
    {
        //information message Function
        public function showMessage(string $message, string $action,string $class):bool{
            header("location:index.php?action=".$action."&message=".$message."&class=".$class);
            exit();
        }

        // Image check for form Function
        public function imageControls(array $filesImage,string $destinationFile):string{
            //accepted extensions
            $validExtensions = ['jpeg','png','jpg'];
            
            $tmpName    = $filesImage["tmp_name"];
            $name       = strtolower($filesImage["name"]);
            $size       = $filesImage["size"];
            $error      = $filesImage["error"];
        
            //get extension of files
            $filesDecomposition = explode('.',$name);
            $filesTitle         = $filesDecomposition[0];
            $filesExtension     = strtolower(end($filesDecomposition));
            $filesSizeMax       = 1000000;

            if(in_array($filesExtension,$validExtensions) && $size <= $filesSizeMax && $error ==0){
                
                //uniquemane with timestamp
                $uniqueName = $filesTitle."_".time().".".$filesExtension;
                move_uploaded_file($tmpName,$destinationFile.$uniqueName);
            }else{
                $this->showMessage("image non valide !","admin","error");
            }

            return $uniqueName;
        }
    }