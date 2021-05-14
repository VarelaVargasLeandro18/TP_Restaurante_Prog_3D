<?php

    /**
     * Representa una Mesa del local.
     */
    class Mesa {

        private string $id;
        private EstadoMesa $estado;

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
         * Get the value of estado
         */ 
        public function getEstado() : EstadoMesa
        {
                return $this->estado;
        }

        /**
         * Set the value of estado
         *
         * @return  self
         */ 
        public function setEstado(EstadoMesa $estado) : self
        {
                $this->estado = $estado;

                return $this;
        }
        
    }