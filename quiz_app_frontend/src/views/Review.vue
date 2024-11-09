<template>
  <v-container>
    <div class="text-center">
      <router-link
        class="text-decoration-none"
        :style="{ color: 'inherit' }"
        to="/"
      >
        <div>
          <v-icon size="40">mdi mdi-cat</v-icon>
          <h1 style="font-size: 24px">ChudyQUIZ</h1>
          <h1>Review</h1>
        </div>
      </router-link>

      <br />
    </div>
    <v-btn
      :color="null"
      variant="outlined"
      prepend-icon="mdi mdi-arrow-left"
      :to="'/'"
      >Home</v-btn
    >
    <div class="mt-5">
      <v-select
        label="Select Topic"
        v-model="payload.selectedSubject"
        width="300px"
        :items="subjects"
        item-title="name"
        item-value="name"
        variant="outlined"
      >
      </v-select>
    </div>
    <v-row v-if="payload.selectedSubject">
      <v-col>
        <div class="" style="height: 90vh; overflow-y: scroll">
          <v-card
            width="1500px"
            color="transparent"
            style="border: 1px solid"
            class="my-16 mx-auto"
            v-for="(item, index) in questions"
            :key="item"
          >
            <div class="pa-12">
              <div class="float-start">
                <div class="d-flex align-center">
                  <div class="me-2">
                    <h2>
                      <b> Question #{{ index + 1 }}</b>
                    </h2>
                  </div>
                  <v-chip class="me-2"> {{ item.difficulty }} </v-chip>
                </div>
              </div>
              <br />
              <div class="float-end">
                <v-icon @click="deleteQuestion(item.id)" color=""
                  >mdi mdi-close</v-icon
                >
              </div>
              <br />
              <v-card>
                <div class="pa-7">
                  <p style="font-size: 14pt">
                    {{ item.question }}
                  </p>
                </div>
              </v-card>
              <div class="pa-3">
                <v-chip class="me-2" rounded="lg"> Answer</v-chip>
                <b>
                  {{ item.correct_answer }}
                </b>
              </div>
              <br />
            </div>
          </v-card>
        </div>
      </v-col>
    </v-row>

    <v-container v-else class="justify-center align-center">
      <div class="text-center">
        <h1 style="opacity: 0.4">Select a Topic</h1>
      </div>
    </v-container>
  </v-container>
</template>
<script>
import Papa from "papaparse";
import {
  createQuestion,
  getQuestions,
  removeQuestion,
  seedQuestions,
} from "../router/api/questionRoutes.js"; // Adjust the path as needed
import { getSubjects } from "../router/api/subjectRoutes.js"; // Adjust the path as needed
export default {
  data() {
    return {
      csv: null,
      questions: [],
      max: 4,
      labels: ["A", "B", "C", "D", "E", "F"], // Define your labels here
      subjects: [],
      selectedChoice: null, //

      payload: {
        selectedSubject: null,
        correctAnswer: null,
        questionType: "mcq",
        difficulty: "2",
        question: "",
        choice: {
          A: null,
          B: null,
          C: null,
          D: null,
          E: null,
          F: null,
        },
      },
    };
  },

  async mounted() {
    this.getAllSubjects();
  },
  watch: {
    "payload.selectedSubject": function (newValue) {
      console.log(newValue);
      this.getSubjectQuestions(newValue);
    },
    "payload.questionType": function (newValue) {
      this.resetFields();
    },
  },
  methods: {
    seed(file) {
      Papa.parse(file, {
        header: true, // Reads the first row as headers
        skipEmptyLines: true, // Skips any empty rows in the CSV
        complete: async (result) => {
          let csvData = result.data;
          if (result.data.length > 0) {
            let csvHeaders = Object.keys(result.data[0]); //
            let response = await seedQuestions(csvData);
            this.getSubjectQuestions(this.payload.selectedSubject);
            console.log(response);
            this.csv = null;
          }
        },
        error: (error) => {
          console.error("Error parsing CSV:", error);
        },
      });
    },
    async deleteQuestion(selectedQuestion) {
      let response = await removeQuestion(selectedQuestion);
      this.getSubjectQuestions(this.payload.selectedSubject);
      console.log(response);
    },
    resetFields() {
      this.payload.correctAnswer = null;
      this.payload.question = null;
      this.payload.difficulty = "2";
      this.labels.forEach((label) => {
        this.payload.choice[label] = null;
      });
      this.selectedChoice = null;
    },
    async getSubjectQuestions(selectedSubject) {
      let response = await getQuestions(selectedSubject);
      console.log(response);

      this.questions = response.data;
      console.log("QUESTIONS!!!", this.questions);
    },
    async getAllSubjects() {
      let response = await getSubjects();
      console.log(response);

      this.subjects = response.data;
    },

    async save(payload) {
      if (payload.questionType == "mcq") {
        if (this.selectedChoice != null) {
          payload.correctAnswer = payload.choice[this.selectedChoice];
        } else {
          alert(`Correct Answer is required.`);
          return;
        }
      }
      console.log("CHOICE", this.selectedChoice);
      const requiredFields = [
        "selectedSubject",
        "correctAnswer",
        "questionType",
        "difficulty",
        "question",
      ];

      // Check if any of the required fields are null or empty
      for (let field of requiredFields) {
        if (payload[field] === null || payload[field] === "") {
          alert(`${field} is required.`);
          return; // Exit the function if any field is invalid
        }
      }

      if (payload.questionType == "mcq") {
        for (let choice in this.labels.slice(0, this.max)) {
          if (payload.choice[choice] === null) {
            alert(`Choice ${choice} is required.`);
            return; // Exit the function if any choice is invalid
          }
        }
      }
      // Check choices

      // If all checks pass, proceed with save logic
      let response = await createQuestion(payload);
      this.getSubjectQuestions(payload.selectedSubject);
      this.resetFields();
    },

    maxFunction(add) {
      let max = 6;
      let min = 3;
      if (add) {
        this.max < max
          ? this.max++
          : alert("Maximum number of choices reached!");
      } else {
        this.max > min
          ? this.max--
          : alert("Minimum number of choices reached!");
      }
    },
  },
};
</script>
