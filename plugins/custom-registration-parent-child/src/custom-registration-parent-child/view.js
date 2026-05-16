import { createRoot } from '@wordpress/element';
import useHandleRegister from './hooks/useHandleRegister';

const RegistrationForm = ({ role }) => {
    const {
        formData,
        status,
        message,
        parentToken,
        handleChange,
        handleSubmit
    } = useHandleRegister(role);

    return (
        <>
            {status === 'error' && (
                <div className="br-alert br-alert-error">
                    <strong>Błąd:</strong> {message}
                </div>
            )}

            {status === 'success' && (
                <div className="br-alert br-alert-success">
                    <strong>Sukces:</strong> Rejestracja zakończona powodzeniem.
                    {parentToken && (
                        <p style={{ marginTop: '10px' }}>
                            Twój kod dla dziecka to: <strong style={{ fontSize: '1.2em' }}>{parentToken}</strong>
                        </p>
                    )}
                    <div className="br-alert-footer">
                        <a href="/logowanie" className="br-go-to-login">
                            Przejdź do logowania
                        </a>
                    </div>
                </div>
            )}
            
            <div className="br-registration-container">
                <form onSubmit={handleSubmit} className="br-registration-form">
                    <div className="br-form-group">
                        <label>Nazwa Użytkownika</label>
                        <input className="br-input-user" placeholder="Twoja nazwa" type="text" name="u_login" value={formData.u_login} onChange={handleChange} required />
                    </div>
                    
                    <div className="br-form-group">
                        <label>E-mail</label>
                        <input className="br-input-email" placeholder="adres@email.com" type="email" name="u_email" value={formData.u_email} onChange={handleChange} required />
                    </div>
                    
                    <div className="br-form-group">
                        <label>Hasło</label>
                        <input className="br-input-password" placeholder="Twoje hasło" type="password" name="u_pass" value={formData.u_pass} onChange={handleChange} required />
                    </div>

                    {role === 'dziecko' && (
                        <div className="br-form-group">
                            <label>Kod Rodzica</label>
                            <input className="br-input-token" placeholder="Kod Rodzica" type="text" name="guardian_token" value={formData.guardian_token} onChange={handleChange} required />
                        </div>
                    )}

                    <button type="submit" className="br-submit-button" disabled={status === 'loading'}>
                        {status === 'loading' ? 'Przetwarzanie...' : 'Zarejestruj się'}
                    </button>
                </form>
            </div>
        </>
    );
};

document.addEventListener('DOMContentLoaded', () => {
    const containers = document.querySelectorAll('.br-react-registration-root');
    containers.forEach((container) => {
        const role = container.getAttribute('data-role');
        const root = createRoot(container);
        root.render(<RegistrationForm role={role} />);
    });
});