import { useState } from '@wordpress/element';

const useHandleRegister = ( role ) => {
	const [ formData, setFormData ] = useState( {
		u_login: '',
		u_email: '',
		u_pass: '',
		guardian_token: '',
	} );
	const [ status, setStatus ] = useState( 'idle' ); // 'idle' | 'loading' | 'success' | 'error'
	const [ message, setMessage ] = useState( '' );
	const [ parentToken, setParentToken ] = useState( '' );

	const handleChange = ( e ) => {
		setFormData( { ...formData, [ e.target.name ]: e.target.value } );
	};

	const handleSubmit = async ( e ) => {
		e.preventDefault();
		setStatus( 'loading' );
		setMessage( '' );

		try {
			const response = await fetch(
				'/wp-json/custom-registration/register',
				{
					method: 'POST',
					headers: { 'Content-Type': 'application/json' },
					body: JSON.stringify( { ...formData, user_role: role } ),
				}
			);

			const data = await response.json();

			if ( response.ok && data.success ) {
				setStatus( 'success' );
				setFormData( {
					u_login: '',
					u_email: '',
					u_pass: '',
					guardian_token: '',
				} );
				if ( data.token ) {
					setParentToken( data.token );
				}
			} else {
				setStatus( 'error' );
				const errorMap = {
					empty_fields: 'Wszystkie pola są wymagane.',
					missing_token: 'Dziecko musi podać kod rodzica.',
					invalid_token: 'Podany kod rodzica jest nieprawidłowy.',
					user_exists:
						'Użytkownik o takim loginie lub e-mailu już istnieje.',
				};
				setMessage(
					errorMap[ data.error ] || 'Wystąpił nieoczekiwany błąd.'
				);
			}
		} catch ( err ) {
			setStatus( 'error' );
			setMessage( 'Błąd połączenia z serwerem. Spróbuj ponownie.' );
		}
	};

	return {
		formData,
		status,
		message,
		parentToken,
		handleChange,
		handleSubmit,
	};
};

export default useHandleRegister;
