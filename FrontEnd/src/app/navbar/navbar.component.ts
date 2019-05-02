import {Component, OnInit} from '@angular/core';
import {QuizService} from '../services/quiz.service';
import {Router} from '@angular/router';

@Component({
    selector: 'app-navbar',
    templateUrl: './navbar.component.html',
    styleUrls: ['./navbar.component.scss']
})
export class NavbarComponent implements OnInit {
    constructor(private router: Router,
                public quizService: QuizService) {
    }

    ngOnInit() {
    }

    goBack() {
        this.quizService.navQuestions('prev');
    }

    goNext() {
        this.quizService.navQuestions('next');

        if (this.quizService.amount === this.quizService.progress) {
            clearInterval(this.quizService.timer);
            this.router.navigate(['/result']);
        }
    }
}
