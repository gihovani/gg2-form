import {Component, OnInit} from '@angular/core';
import {Router} from '@angular/router';
import {QuizService} from '../shared/quiz.service';
import {Answer} from '../model';
import {environment} from '../../environments/environment';

@Component({
  selector: 'app-result',
  templateUrl: './result.component.html',
  styleUrls: ['./result.component.scss']
})
export class ResultComponent implements OnInit {
  public config = environment;

  constructor(private router: Router, public quizService: QuizService) {
  }

  ngOnInit() {
    if (this.quizService.questions === undefined) {
      this.router.navigate(['/quiz']);
      return;
    }
    this.showAnswers();
  }

  showAnswers() {
    const anwsers = [];
    this.quizService.questions.forEach((i) => {
      const tmp: Answer = {
        user_id: this.quizService.userId,
        question_id: this.quizService.surveyId,
        question: i.title,
        answer: i.answer,
        survey_id: i.id
      };
      anwsers.push(tmp);
    });
  }
}
