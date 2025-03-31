// server/api/import-data.js
import { defineEventHandler, readBody } from 'h3';

export default defineEventHandler(async (event) => {
  const body = await readBody(event);
  const rowData = body.row;

  try {
    // Ici, vous devrez intégrer votre logique pour enregistrer rowData dans votre base de données.
    // Exemple (à adapter à votre base de données) :
    // await MyDatabaseModel.create(rowData);

    console.log('Ligne importée avec succès:', rowData);
    return { success: true };
  } catch (error) {
    console.error('Erreur lors de l\'enregistrement de la ligne:', rowData, error);
    event.res.statusCode = 500;
    return { error: 'Erreur lors de l\'enregistrement dans la base de données' };
  }
});