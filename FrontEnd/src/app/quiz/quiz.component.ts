import {Component, OnInit} from '@angular/core';
import {ActivatedRoute, Router} from '@angular/router';
import {QuizService} from '../shared/quiz.service';

import {animate, style, transition, trigger} from '@angular/animations';
import {Answer, Question} from '../model';
import {environment} from '../../environments/environment';

@Component({
  selector: 'app-quiz',
  templateUrl: './quiz.component.html',
  styleUrls: ['./quiz.component.scss'],
  animations: [
    trigger('questions', [
      transition('*=>*', [
        style({
          opacity: 0,
          transform: 'translateY(-20px)'
        }),
        animate('0.2s', style({
          opacity: 1,
          transform: 'translateY(0)'
        }))
      ])
    ])
  ]
})
export class QuizComponent implements OnInit {
  public open: boolean;
  public config = environment;
  public loaded = false;
  public showIntro = null;
  private _checkedAnswers = [];

  constructor(private router: Router, public quizService: QuizService, private route: ActivatedRoute) {
  }

  ngOnInit() {
    this.open = false;
    this.quizService.seconds = 0;
    this.getParamValues();
    this.quizService.getQuestions(this.quizService.surveyId)
      .subscribe((questions: Question[]) => {
        this.loaded = true;
        this.showIntro = this.hasIntro(this.quizService.surveyId);
        this.quizService.amount = questions.length;
        this.quizService.progress = 0;
        this.quizService.questions = questions;
        this.quizService.setQuestion(0);
        this.startTimer();
      }, error => {
        this.loaded = true;
      });
  }

  private hasIntro(surveyId): string | null {
    if (this.config.imgIntro.hasOwnProperty(surveyId)) {
      return this.config.imgIntro[surveyId];
    }
    return null;
  }

  start() {
    this.showIntro = null;
  }

  getParamValues() {
    this.route.queryParams
      .subscribe(params => {
        this.quizService.userId = parseInt(params['user'], 10);
        this.quizService.surveyId = parseInt(params['id'], 10);
      });
  }

  displayProgress() {
    return this.quizService.progress * 100 / this.quizService.amount;
  }

  startTimer() {
    this.quizService.timer = setInterval(() => {
      this.quizService.seconds++;
    }, 1000);
  }

  clickAnswer(questionId, question, answer) {
    this.open = false;
    this.quizService.questionActive['answer'] = answer;
    this.quizService.answersLocalStorage();
    const tmp: Answer = {
      user_id: this.quizService.userId,
      survey_id: this.quizService.surveyId,
      question: question,
      answer: answer,
      question_id: questionId
    };
    this.quizService.sendAnsers([tmp]);
    this.quizService.navQuestions('next');

    if (this.quizService.amount <= this.quizService.progress) {
      clearInterval(this.quizService.timer);
      this.router.navigate(['/result']);
    }
  }

  changeCheckbox(value, checked) {
    if (checked) {
      this._checkedAnswers.push(value);
    } else {
      const index = this._checkedAnswers.indexOf(value);
      this._checkedAnswers.splice(index, 1);
    }
  }

  clickAnswerCheckbox(questionId, question) {
    this.clickAnswer(questionId, question, JSON.stringify(this._checkedAnswers));
  }
}
