import axios from "axios";
const URL = import.meta.env.VITE_URL;

const getSubjects = async () => {
  try {
    let response = await axios.get(URL + "get_subjects");
    console.log(response.data);
    return response.data;
  } catch (err) {
    return err.response;
  }
};

const addNewSubject = async (name) => {
  try {
    let response = await axios.get(URL + "add_subject?name=" + name);
    console.log(response.data);
    return response.data;
  } catch (err) {
    return err.response;
  }
};

const removeSubject = async (subject) => {
  try {
    let response = await axios.get(URL + "delete_subject?subject=" + subject);
    console.log(response.data);
    return response.data;
  } catch (err) {
    return err.response;
  }
};

export { getSubjects, addNewSubject, removeSubject };
