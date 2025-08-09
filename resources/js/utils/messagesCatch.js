import exceptions from "@/utils/exceptions";

export function catchDefault (e)  {
	console.error("Erro",e.response.data.message )
	if(e.response.data  && e.response.data.message === 'Token has expired'){
		return [ exceptions['TokenHasExpired'], 'alert-danger'];
	}
	if(e.response.data.errors && e.code === 'ERR_BAD_REQUEST' ){
		let aux = e.response.data.errors;
		let error ="";
		Object.keys(aux).forEach(key => {
			error += aux[key]+" ";
		});
		
		return [error , 'alert-danger'];
		
	}
	if(e.code && e.code === 'ERR_BAD_REQUEST' ){
		
		
		if( e.response.data.message == 'MusicException'){
			return [ e.response.data.exception, 'alert-danger']
		}
		if(e.code == 'ERR_BAD_REQUEST'){
			return [ "Não autorizado", 'alert-danger']
		}else{
			const error = e.response.data.exception;
			return [error , 'alert-danger'];
		}
		
	} 
	if(e.code && e.code === 'ERR_BAD_RESPONSE' ){
		const error = exceptions[e.response.data.message] == undefined ? 'Erro no envio': exceptions[e.response.data.message];
		return [ error, 'alert-danger'];

	}
	else{
		return [ "Erro indefinido", 'alert-danger'];
	}

}