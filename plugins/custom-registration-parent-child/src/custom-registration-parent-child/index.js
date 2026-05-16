import { registerBlockType } from '@wordpress/blocks';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, SelectControl } from '@wordpress/components';
import metadata from './block.json';
import './style.scss';
import './editor.scss';

registerBlockType(metadata.name, {
    edit: ({ attributes, setAttributes }) => {
        return (
            <div style={{ padding: '20px', border: '1px dashed #ccc', textAlign: 'center' }}>
                <InspectorControls>
                    <PanelBody title="Ustawienia Formularza">
                        <SelectControl
                            label="Dla kogo jest ten formularz?"
                            value={attributes.role}
                            options={[
                                { label: 'Rodzic', value: 'rodzic' },
                                { label: 'Dziecko', value: 'dziecko' },
                            ]}
                            onChange={(newRole) => setAttributes({ role: newRole })}
                        />
                    </PanelBody>
                </InspectorControls>
                <h3>Formularz Rejestracji: {attributes.role === 'rodzic' ? 'Rodzic' : 'Dziecko'}</h3>
            </div>
        );
    },
});