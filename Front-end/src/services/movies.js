import api from "./api";
/**
 *@param {number} page
 *@param {number} itemsPerPage
 *@returns {Promise}
 */

export const getMovies = async (page = 1, itemsPerPage = 20) => {
  const response = await api.get(
    `/movies?page=${page}&itemsPerpage=${itemsPerPage}`
  );
  return response.data;
};

/**
 *@param {number} id
 *@returns {Promise}
 */
export const getMovie = async (id) => {
  const response = await api.get(`/movies/${id}`);
  return response.data;
};

/**
 *@param {string} query
 *@returns {Promise}
 */

export const searchMovies = async (query) => {
  const response = await api.get(`/movies?title=${query}`);
  return response.data;
};
/**
 *@param {string} genre
 *@returns {Promise}
 */
export const getMoviesByGenre = async (genre) => {
  const response = await api.get(`/movies?genre=${genre}`);
  return response.data;
};
