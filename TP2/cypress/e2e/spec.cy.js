describe('Test E2E - Ajout, Modification, Suppression', () => {
  it('Ajoute, modifie et supprime un élément', () => {
    cy.visit('http://localhost:8888/TP2/');

    // Ajout
    cy.get('#name').type('TestToto');
    cy.get('#email').type('test.toto@gmail.com');
    cy.get('button').click();

    // Vérifier que l'élément ajouté est bien présent dans la liste
    cy.get('li').should('contain.text', 'TestToto (test.toto@gmail.com)');

    // Modification
    cy.get('button:nth-child(1)').click(); // Cliquer sur le bouton modifier
    cy.get('#name').clear().type('TestTotoModifE2E');
    cy.get('button:nth-child(4)').click(); // Sauvegarder la modification

    // Vérifier que l'ancienne valeur n'est plus présente et que la nouvelle est bien affichée
    cy.get('li').should('not.contain.text', 'TestToto (test.toto@gmail.com)');
    cy.get('li').should('contain.text', 'TestTotoModifE2E (test.toto@gmail.com)');

    // Suppression
    cy.get('button:nth-child(2)').click(); // Cliquer sur le bouton supprimer

    // ✅ Correction : Vérifier que `li` n'existe plus au lieu de vérifier son contenu
    cy.get('li').should('not.exist');
  });
});
