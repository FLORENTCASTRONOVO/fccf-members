(function (wp) {
  const { registerBlockType } = wp.blocks;
  const { InspectorControls } = wp.blockEditor;
  const { PanelBody, RangeControl } = wp.components;
  const { __ } = wp.i18n;
  const el = wp.element.createElement;

  registerBlockType('fccf/download-list', {
    title: __('Liste de téléchargements', 'fccf-members'),
    description: __('Affiche les fichiers protégés réservés aux membres.', 'fccf-members'),
    icon: 'download',
    category: 'widgets',
    attributes: {
      limit: { type: 'number', default: 50 }
    },

    edit: (props) => {
      const { attributes: { limit }, setAttributes } = props;

      return el('div', {},
        el(InspectorControls, {},
          el(PanelBody, { title: __('Réglages', 'fccf-members') },
            el(RangeControl, {
              label: __('Nombre de fichiers à afficher', 'fccf-members'),
              min: 1,
              max: 200,
              value: limit,
              onChange: (v) => setAttributes({ limit: v })
            })
          )
        ),
        el('p', {}, __('Liste de téléchargements (prévisualisation) — limite : ', 'fccf-members') + limit)
      );
    },

    save: () => null // Rendu 100% côté serveur
  });
})(window);
