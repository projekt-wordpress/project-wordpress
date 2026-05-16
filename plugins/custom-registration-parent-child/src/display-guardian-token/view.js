import { createRoot } from '@wordpress/element';
import useFetchToken from './hooks/useFetchToken';

const TokenDisplay = ({ nonce }) => {
    const { token, status } = useFetchToken(nonce);

    if (status === 'loading' || status === 'error') {
        return null;
    }

    return (
        <div className="br-token-display-box">
            <span className="br-token-label">Twój token rodzica to:</span>
            <strong className="br-token-value">{token}</strong>
        </div>
    );
};

document.addEventListener('DOMContentLoaded', () => {
    const containers = document.querySelectorAll('.br-token-wrapper');
    
    containers.forEach((container) => {
        const nonce = container.getAttribute('data-nonce');
        const root = createRoot(container);
        root.render(<TokenDisplay nonce={nonce} />);
    });
});