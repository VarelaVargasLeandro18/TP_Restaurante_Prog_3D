<?php

    class PermisosEmpleadoMesa {

        private int $id;
        private TipoEmpleado $empleado;
        private EstadoMesa $estadoMesa;

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
         * Get the value of empleado
         */ 
        public function getEmpleado() : TipoEmpleado
        {
                return $this->empleado;
        }

        /**
         * Set the value of empleado
         *
         * @return  self
         */ 
        public function setEmpleado(TipoEmpleado $empleado) : self
        {
                $this->empleado = $empleado;

                return $this;
        }

        /**
         * Get the value of estadoMesa
         */ 
        public function getEstadoMesa() : EstadoMesa
        {
                return $this->estadoMesa;
        }

        /**
         * Set the value of estadoMesa
         *
         * @return  self
         */ 
        public function setEstadoMesa(EstadoMesa $estadoMesa) : self
        {
                $this->estadoMesa = $estadoMesa;

                return $this;
        }
    }