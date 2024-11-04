<template>
  <v-container>
    <div class="text-center">
      <v-icon size="40">mdi mdi-cat</v-icon>
      <h1 style="font-size: 24px">ChudyQUIZ</h1>
      <br />
    </div>
    <v-btn
      variant="outlined"
      prepend-icon="mdi mdi-arrow-left"
      color="white"
      :to="'/'"
      >Home</v-btn
    >
    <div class="mt-5">
      <v-select
        label="Select a Subject"
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
      <v-col cols="12" lg="6">
        <div>
          <v-card variant="outlined" class="my-5">
            <div class="pa-10">
              <div class="mb-5">
                <h2>
                  <b>Create Question </b>
                  <v-chip>
                    {{ payload.selectedSubject }}
                  </v-chip>
                </h2>
              </div>
              <br />
              <v-row>
                <v-col>
                  Question Type
                  <v-radio-group inline v-model="payload.questionType">
                    <v-radio label="Multiple Choice" value="mcq"></v-radio>
                    <v-radio label="Identification" value="identify"></v-radio>
                  </v-radio-group>
                </v-col>
                <v-col>
                  Difficulty
                  <v-radio-group v-model="payload.difficulty">
                    <v-radio label="Easy" value="1"></v-radio>
                    <v-radio label="Average" value="2"></v-radio>
                    <v-radio label="Difficult" value="3"></v-radio>
                  </v-radio-group>
                </v-col>
              </v-row>
              <br />

              <br />
              <v-textarea
                variant="outlined"
                rounded="xl"
                v-model="payload.question"
                label="Question"
              ></v-textarea>

              <!-- Choices/Answer -->
              <div v-if="payload.questionType == 'mcq'">
                <v-row no-gutters>
                  <v-col
                    v-for="(label, index) in labels.slice(0, max)"
                    :key="index"
                    cols="12"
                    sm="6"
                  >
                    <v-text-field
                      class="mx-4"
                      density="default"
                      :label="label"
                      v-model="payload.choice[label]"
                    ></v-text-field>
                  </v-col>
                </v-row>

                <br />
                <v-row>
                  <v-col>
                    <v-select
                      width="200px"
                      label="Correct Answer"
                      :items="labels.slice(0, max)"
                      variant="outlined"
                      v-model="selectedChoice"
                    ></v-select>
                  </v-col>
                  <v-col>
                    <br />
                    Add Choices
                    <v-btn-group>
                      <v-btn
                        class="mx-2"
                        @click="maxFunction(true)"
                        rounded="lg"
                        color="white"
                        icon="mdi mdi-plus"
                        variant="flat"
                      ></v-btn>
                      <v-btn
                        @click="maxFunction(false)"
                        rounded="lg"
                        color="white"
                        icon="mdi mdi-minus"
                        variant="flat"
                      ></v-btn>
                    </v-btn-group>
                  </v-col>
                </v-row>
              </div>
              <div v-else>
                <v-text-field
                  v-model="payload.correctAnswer"
                  label="Answer"
                  density="default"
                ></v-text-field>
              </div>
              <v-card-actions class="justify-end w-100">
                <v-btn
                  @click="save(payload)"
                  width="100"
                  append-icon="mdi mdi-plus"
                  variant="flat"
                  color="blue"
                  >ADD
                </v-btn>
              </v-card-actions>
            </div>
          </v-card>
        </div>
      </v-col>
      <v-col>
        <div class="mt-12" style="height: 90vh; overflow-y: scroll">
          <v-card
            variant="outlined"
            class="ma-5"
            v-for="item in 10"
            :key="item"
          >
            <div class="pa-5">
              <div class="float-start">
                <div class="d-flex align-center">
                  <div class="me-2">
                    <h3>
                      <b> Question #{{ item }}</b>
                    </h3>
                  </div>
                  <v-chip> Easy </v-chip>
                </div>
              </div>
              <br />
              <div class="float-end">
                <v-icon color="">mdi mdi-close</v-icon>
              </div>

              <div class="mt-8">
                lorem sdadasasdsadasddadasdadasdasdasdasddas
              </div>
              <br />
            </div>
          </v-card>
        </div>
      </v-col>
    </v-row>

    <v-container v-else class="justify-center align-center">
      <div class="text-center">
        <h1 style="opacity: 0.4">Select a Subject</h1>
      </div>
    </v-container>
  </v-container>
</template>
<script>
import { createQuestion, getQuestions } from "../router/api/questionRoutes.js"; // Adjust the path as needed
import {
  getSubjects,
  addNewSubject,
  removeSubject,
} from "../router/api/subjectRoutes.js"; // Adjust the path as needed
export default {
  data() {
    return {
      questions: [],
      max: 4,
      labels: ["A", "B", "C", "D", "E", "F"], // Define your labels here
      subjects: [],
      selectedChoice: null, //

      payload: {
        selectedSubject: null,
        correctAnswer: null,
        questionType: "mcq",
        difficulty: null,
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
      this.payload.correctAnswer = null;
      this.payload.question = null;
      this.payload.difficulty = null;
      this.labels.forEach((label) => {
        this.payload.choice[label] = null;
      });

      this.selectedChoice = null;
    },
  },
  methods: {
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
        payload.correctAnswer = payload.choice[this.selectedChoice];
      }

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
      alert("All fields are valid. Proceeding with save...");
      let response = await createQuestion(payload);
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
