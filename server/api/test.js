// server/api/test.js
import { defineEventHandler } from 'h3';

export default defineEventHandler(async (event) => {
  const query = getQuery(event);
  const body = await readBody(event);

  console.log('Requête reçue sur /api/test');
  console.log('Query parameters:', query);
  console.log('Body:', body);

  return {
    message: 'Réponse de l\'API /api/test',
    data: {
      fromQuery: query.name,
      fromBody: body
    }
  };
});