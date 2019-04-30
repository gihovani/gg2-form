import {BrowserModule} from '@angular/platform-browser';
import {NgModule} from '@angular/core';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {BrowserAnimationsModule} from '@angular/platform-browser/animations';

import {AppComponent} from './app.component';
import {NavbarComponent} from './navbar/navbar.component';
import {QuizComponent} from './quiz/quiz.component';
import {ResultComponent} from './result/result.component';
import {RouterModule} from '@angular/router';
import {appRoutes} from './routes';
import {HttpClientModule} from '@angular/common/http';
import {QuizService} from './shared/quiz.service';

@NgModule({
    declarations: [
        AppComponent,
        NavbarComponent,
        QuizComponent,
        ResultComponent
    ],
    imports: [
        BrowserModule,
        FormsModule,
        HttpClientModule,
        RouterModule.forRoot(appRoutes),
        BrowserAnimationsModule,
        ReactiveFormsModule
    ],
    providers: [QuizService],
    bootstrap: [AppComponent]
})
export class AppModule {
}
