<?php

	class ConferindoMaterialHelper{

		static function itensToJson( $itens ){

			$json = null;
			if( !empty( $itens ) ){

				foreach ($itens as $item) {
					$json[] = (integer) $item->isa_idproduto;
				}
				return json_encode( $json );

			}
			else{
				return false;
			}

		}

	}