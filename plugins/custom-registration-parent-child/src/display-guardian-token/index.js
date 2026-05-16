import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import metadata from './block.json';
import './style.scss';

registerBlockType(metadata.name, {
    edit: () => {
        const blockProps = useBlockProps({
            style: { padding: '15px', backgroundColor: '#f0f0f1', textAlign: 'center' }
        });
        return (
            <div { ...blockProps }>
                <p>Tutaj wyświetli się kod rodzica.</p>
            </div>
        );
    },
});