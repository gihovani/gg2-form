import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {environment} from '../../environments/environment';
import {Answer, Question} from '../model';
import {Observable} from 'rxjs';
import {map} from 'rxjs/operators';

@Injectable()
export class QuizService {
  readonly rootUrl = environment.apiUrl;
  public questions: Question[];
  public questionActive: Question;
  public seconds: number;
  public timer;
  public progress: number;
  public amount: number;
  public userId: number;
  public surveyId: number;
  public answers = {
    surveyId: null,
    question: {
      id: null,
      answer: null
    }
  };

  constructor(private http: HttpClient) {
    this.progress = 0;
    localStorage.clear();
  }

  navQuestions(action) {
    this.progress += (action === 'next') ? 1 : -1;
    this.setQuestion(this.progress);
  }

  answersLocalStorage() {
    const old = JSON.parse(localStorage.getItem('answers')) || [];
    this.answers = {
      surveyId: 0,
      question: {
        id: this.questionActive['id'],
        answer: this.questionActive['answer']
      }
    };
    old.push(this.answers);
    localStorage.setItem('answers', JSON.stringify(old));
  }

  getQuestions(surveyId): Observable<Question[]> {
    return this.http.get<{ data: Question[] }>(`${this.rootUrl}/question.php?id=${surveyId}`)
      .pipe(map(response => response.data));
  }

  setQuestion(pos) {
    this.questionActive = this.questions[pos];
  }

  getAnswer(surveyId): Observable<Answer[]> {
    return this.http.get<{ data: Answer[] }>(`${this.rootUrl}/answer.php?id=${surveyId}`)
      .pipe(map(response => response.data));
  }

  sendAnswer(answer: Answer): Observable<any> {
    return this.http.post(`${this.rootUrl}/answer.php`, answer);
  }

  sendMail(answers: Answer[]): Observable<any> {
    return this.http.post(`${this.rootUrl}/email.php`, {answers: answers});
  }
}
