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
          <h1>Add Questions</h1>
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
    <div class="d-flex justify-end">
      <v-chip
        @click="showReason = !showReason"
        :color="showReason ? 'red' : 'green'"
        rounded="lg"
      >
        {{ showReason ? "Hide" : "Show" }} Reason</v-chip
      >
    </div>
    <v-row v-if="payload.selectedSubject">
      <v-col cols="12" lg="6">
        <div>
          <v-card color="transparent" style="border: 1px solid" class="my-5">
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
                <br />
                <v-textarea
                  v-model="payload.reason"
                  label="Reason"
                  rounded="xl"
                  variant="outlined"
                ></v-textarea>
              </div>
              <div v-else>
                <v-text-field
                  v-model="payload.correctAnswer"
                  label="Answer"
                  density="default"
                ></v-text-field>

                <v-textarea
                  v-model="payload.reason"
                  label="Reason"
                  rounded="xl"
                  variant="outlined"
                ></v-textarea>
              </div>
              <v-card-actions class="justify-end w-100">
                <v-btn
                  @click="save(payload)"
                  append-icon="mdi mdi-plus"
                  variant="flat"
                  color="blue"
                  >Save Question
                </v-btn>
              </v-card-actions>
            </div>
          </v-card>
        </div>
        <v-card variant="outlined">
          <div class="pa-10">
            <div class="mb-5">
              <h2>
                <b>Seed from CSV </b>
              </h2>
            </div>
            <v-file-input
              v-model="csv"
              variant="outlined"
              rounded="xl"
              label="Upload CSV"
            ></v-file-input>
            <v-card-actions class="justify-end">
              <v-btn
                :disabled="csv != null ? false : true"
                append-icon="mdi mdi-seed"
                variant="flat"
                @click="seed(csv)"
                >SEED</v-btn
              >
            </v-card-actions>
          </div>
        </v-card>
      </v-col>
      <v-col>
        <div class="mt-5" style="height: 90vh; overflow-y: scroll">
          <v-card
            color="transparent"
            style="border: 1px solid"
            class="ma-5"
            v-for="(item, index) in questions"
            :key="item"
          >
            <div class="pa-5">
              <div class="d-flex">
                <div class="d-flex ga-2">
                  <h2>
                    <b> Question #{{ index + 1 }}</b>
                  </h2>
                  <v-chip class="me-2"> {{ item.difficulty }} </v-chip>
                </div>
                <v-spacer />
                <div>
                  <v-icon @click="deleteQuestion(item.id)" color=""
                    >mdi mdi-close</v-icon
                  >
                </div>
              </div>
              <br />

              <v-card>
                <div class="pa-7">
                  <p style="font-size: 11pt">
                    {{ item.question }}
                  </p>
                </div>
              </v-card>
              <div class="pa-3 d-flex align-center ga-2">
                <v-chip class="" rounded="lg"> Answer</v-chip>
                <b>
                  {{ item.correct_answer }}
                </b>
                <v-spacer />
              </div>
              <v-card
                max-width="400px"
                v-if="showReason"
                style="font-size: 10pt"
                rounded="lg"
                variant="tonal"
                class="pa-3 ma-auto"
              >
                {{ item.reason ? item.reason : "Reason Not Provided" }}
              </v-card>

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
      showReason: false,
      csv: null,
      questions: [],
      max: 4,
      labels: ["A", "B", "C", "D", "E", "F"], // Define your labels here
      subjects: [],
      selectedChoice: null, //

      payload: {
        selectedSubject: null,
        correctAnswer: null,
        reason: null,
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
      this.payload.reason = null;
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
        "reason",
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
