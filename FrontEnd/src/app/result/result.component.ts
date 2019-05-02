import {Component, OnInit} from '@angular/core';
import {Router} from '@angular/router';
import {QuizService} from '../services/quiz.service';
import {Answer} from '../model';
import {environment} from '../../environments/environment';

@Component({
  selector: 'app-result',
  templateUrl: './result.component.html',
  styleUrls: ['./result.component.scss']
})
export class ResultComponent implements OnInit {
  public config = environment;

  constructor(private router: Router,
              private quizService: QuizService) {
  }

  ngOnInit() {
    if (this.quizService.questions === undefined) {
      this.router.navigate(['/quiz']);
      return;
    }
    this.sendMail();
  }

  sendMail() {
    const anwsers = [];
    this.quizService.questions.forEach((i) => {
      const tmp: Answer = {
        question_id: i.id,
        question: i.title,
        answer: i.answer,
        user_id: this.quizService.userId,
        survey_id: this.quizService.surveyId
      };
      anwsers.push(tmp);
    });

    this.quizService.sendMail(anwsers).subscribe(data => {
      console.log('email enviado', data);
    });
  }
}
