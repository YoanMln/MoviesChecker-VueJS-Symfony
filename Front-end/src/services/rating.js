import api from "./api";

export const rateMovie = async (movieId, value) => {
  const response = await api.post("/ratings", {
    movie: `/api/movies/${movieId}`,
    value: value,
  });
  return response.data;
};

export const getUserRatingForMovie = async (movieId) => {
  const response = await api.get("/ratings", {
    params: {
      "movie.id": movieId,
    },
  });
  const ratings = response.data["hydra:member"];
  return ratings.length > 0 ? ratings[0] : null;
};

export const deleteRating = async (ratingId) => {
  await api.delete(`/ratings/${ratingId}`);
};

export const updateRating = async (ratingId, value) => {
  const response = await api.patch(
    `/ratings/${ratingId}`,
    {
      value: value,
    },
    {
      headers: {
        "Content-Type": "application/merge-patch+json",
      },
    }
  );
  return response.data;
};
