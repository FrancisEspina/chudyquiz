import axios from "axios";
const URL = import.meta.env.VITE_URL;

const createQuestion = async (payload) => {
  console.log(payload);
  try {
    let response = await axios.get(
      URL + "create_question?payload=" + JSON.stringify(payload)
    );
    console.log(response.data);
    return response.data;
  } catch (err) {
    return err.response;
  }
};

const removeQuestion = async (question_id) => {
  console.log(question_id);
  try {
    let response = await axios.get(
      URL + "remove_question?question_id=" + question_id
    );
    console.log(response.data);
    return response.data;
  } catch (err) {
    return err.response;
  }
};

const getQuestions = async (subject) => {
  console.log(subject);
  try {
    let response = await axios.get(URL + "get_questions?subject=" + subject);
    console.log(response.data);
    return response.data;
  } catch (err) {
    return err.response;
  }
};
export { createQuestion, getQuestions, removeQuestion };
