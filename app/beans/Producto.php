<?php

    /**
     * Representa un Producto que brinda un Sector determinado del local.
     */
    class Producto {

        private int $id;
        private string $nombre;
        private string $tipo;
        private Sector $sector;

        /**
         * Get the value of id
         */ 
        public function getId() : int
        {
                return $this->id;
        }

        /**
         * Set the value of id
         *
         * @return  self
         */ 
        public function setId(int $id) : self
        {
                $this->id = $id;

                return $this;
        }

        /**
         * Get the value of nombre
         */ 
        public function getNombre() : string
        {
                return $this->nombre;
        }

        /**
         * Set the value of nombre
         *
         * @return  self
         */ 
        public function setNombre(string $nombre) : self
        {
                $this->nombre = $nombre;

                return $this;
        }

        /**
         * Get the value of tipo
         */ 
        public function getTipo() : string
        {
                return $this->tipo;
        }

        /**
         * Set the value of tipo
         *
         * @return  self
         */ 
        public function setTipo(string $tipo)
        {
                $this->tipo = $tipo;

                return $this;
        }

        /**
         * Get the value of sector
         */ 
        public function getSector() : Sector
        {
                return $this->sector;
        }

        /**
         * Set the value of sector
         *
         * @return  self
         */ 
        public function setSector(Sector $sector) : self
        {
                $this->sector = $sector;

                return $this;
        }
    }