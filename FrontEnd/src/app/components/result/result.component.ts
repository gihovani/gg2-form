import {Component, OnInit} from '@angular/core';
import {ActivatedRoute, Router} from '@angular/router';
import {QuizService} from '../../services/quiz.service';
import {environment} from '../../../environments/environment';
import {Answer} from '../../model';

@Component({
  selector: 'app-result',
  templateUrl: './result.component.html',
  styleUrls: ['./result.component.scss']
})
export class ResultComponent implements OnInit {
  config = environment;
  answers: Answer[] = [];
  loading = true;

  constructor(private router: Router,
              private route: ActivatedRoute,
              private quizService: QuizService) {
  }

  ngOnInit() {
    this.loading = true;
    this.route.queryParams
      .subscribe(params => {
        const surveyId = parseInt(params['id'], 10);
        this.quizService.surveyId = surveyId;
        this.quizService.getAnswer(surveyId).subscribe(answers => {
          this.answers = answers;
          console.log(answers);
          this.loading = false;
        }, error => {
          console.log('Erro: não foi possivel encontrar respostas', error);
          this.loading = false;
        });
      });
  }
}
