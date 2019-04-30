export interface Question {
  id?: number;
  name?: string;
  answer?: string;
  answers?: { description: string, type: string, id: number }[];
  image?: string;
  title: string;
  type: string;
}

export interface Answer {
  user_id: number;
  survey_id: number;
  question: string;
  answer: string;
  question_id: number;
}
